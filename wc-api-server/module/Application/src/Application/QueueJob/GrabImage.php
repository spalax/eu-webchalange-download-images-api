<?php
namespace Application\QueueJob;

use Application\Service\Storage\StorageInterface;
use Application\V1\Entity\Images;
use Application\V1\Entity\PageInterface;
use Application\V1\Entity\Pages;
use Aws;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use SlmQueue\Job\AbstractJob;
use SlmQueueDoctrine\Job\Exception\ReleasableException;
use Zend\Dom\Document;
use Zend\Http\Client as HttpClient;

class GrabImage extends AbstractJob
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var StorageInterface
     */
    protected $storageService;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient,
                                EntityManagerInterface $entityManager,
                                StorageInterface $storageService)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->storageService = $storageService;
    }

    /**
     * @param int $pageId
     *
     * @return Pages
     */
    protected function updatePending($pageId)
    {
        $pageEntity = $this->entityManager
                           ->find( 'Application\V1\Entity\Pages', $pageId,
                                    LockMode::PESSIMISTIC_WRITE);

        $imagesCnt = $pageEntity->getPendingImagesCnt();

        if ( $imagesCnt > 0 ) {
            $pageEntity->setPendingImagesCnt( $imagesCnt - 1 );
            if ( $pageEntity->getPendingImagesCnt() == 0 ) {
                $pageEntity->setStatus(PageInterface::STATUS_DONE);
            }
        }

        return $pageEntity;
    }

    /**
     * @param $imagetype
     *
     * @return bool|string
     */
    protected function get_extension($imagetype)
    {
        if(empty($imagetype)) {
            return false;
        }
        switch($imagetype)
        {
            case 'image/bmp': return 'bmp';
            case 'image/cis-cod': return 'cod';
            case 'image/gif': return 'gif';
            case 'image/ief': return 'ief';
            case 'image/jpeg': return 'jpg';
            case 'image/pipeg': return 'jfif';
            case 'image/tiff': return 'tif';
            case 'image/x-cmu-raster': return 'ras';
            case 'image/x-cmx': return 'cmx';
            case 'image/x-icon': return 'ico';
            case 'image/svg+xml': return 'svg';
            case 'image/x-portable-anymap': return 'pnm';
            case 'image/x-portable-bitmap': return 'pbm';
            case 'image/x-portable-graymap': return 'pgm';
            case 'image/x-portable-pixmap': return 'ppm';
            case 'image/x-rgb': return 'rgb';
            case 'image/x-xbitmap': return 'xbm';
            case 'image/x-xpixmap': return 'xpm';
            case 'image/x-xwindowdump': return 'xwd';
            case 'image/png': return 'png';
            case 'image/x-jps': return 'jps';
            case 'image/x-freehand': return 'fh';
            default: return false;
        }
    }

    public function execute()
    {
        $this->entityManager
             ->getConnection()
             ->setTransactionIsolation(Connection::TRANSACTION_SERIALIZABLE);

        $this->entityManager->beginTransaction();

        try {
            $payload = $this->getContent();
            $ext = false;

            echo "processing >> " . $payload['image_src'] .
                 " >> for id >> " . $payload['page_id'] .
                 " >> for ext >> " . $payload['image_ext'] . "\n";

            try {
                $this->httpClient->setUri( $payload['image_src'] );
                $this->httpClient->getRequest()->setMethod('HEAD');
                $response = $this->httpClient->send();
                if ($response->getHeaders()->get('Content-Type') !== false) {
                    $ext = $this->get_extension($response->getHeaders()->get('Content-Type')->getFieldValue());
                }
            } catch (\Zend\Http\Exception\InvalidArgumentException $e) {
                echo "Exception: while sending HEAD method >> " . $e->getMessage() . "\n";
            }

            echo "declared ext >> " . $payload['image_ext'] . " >> detected ext >> ". $ext. "\n";
            if ($ext === false) {
                echo "Not an image \n";
                $pageEntity = $this->updatePending($payload['page_id']);

                echo "processed >> " . $payload['image_src'] .
                     " >> pendingCnt >> " . $pageEntity->getPendingImagesCnt() .
                     " >> status >> " . $pageEntity->getStatus() . "\n";

                $this->entityManager->flush();
                $this->entityManager->commit();
                return;
            }

            $this->httpClient->getRequest()->setMethod('GET');
            $response  = $this->httpClient->send();

            if ($ext == 'svg') {
                $xmlget = simplexml_load_string($response->getBody());
                $xmlattributes = $xmlget->attributes();
                $width = preg_replace('/[^0-9.]/', '', strtolower((string) $xmlattributes->width));
                $height = preg_replace('/[^0-9.]/', '', strtolower((string) $xmlattributes->height));
                $imageInfo = [$width, $height, 'mime'=>$response->getHeaders()->get('Content-Type')->getFieldValue()];
                $imageSize = mb_strlen($response->getBody(), '8bit');
            } else {
                $imageInfo = getimagesizefromstring( $response->getBody() );
                $contentLength = $response->getHeaders()
                                          ->get( 'Content-Length' );
                if ($contentLength === false) {
                    $imageSize = mb_strlen($response->getBody(), '8bit');
                } else {
                    $imageSize = $contentLength->getFieldValue();
                }
            }

            $url = $this->storageService
                        ->store( $payload['image_ext'], $response->getBody() );

            $imageEntity = new Images();

            $imageEntity->setContentType( $imageInfo['mime'] );
            $imageEntity->setWidth( $imageInfo[0] );
            $imageEntity->setHeight( $imageInfo[1] );
            $imageEntity->setSize( $imageSize );
            $imageEntity->setLocalPath( $url );
            $imageEntity->setRemotePath( $payload['image_src'] );

            $pageEntity = $this->updatePending($payload['page_id']);

            $imageEntity->setPage( $pageEntity );
            $this->entityManager->persist( $imageEntity );

            echo "processed >> " . $payload['image_src'] .
                 " >> pendingCnt >> " . $pageEntity->getPendingImagesCnt() .
                 " >> status >> " . $pageEntity->getStatus() . "\n";

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            echo "Exception : >>>> ". $e->getMessage(). "\n";
            throw new ReleasableException(array('priority' => 10, 'delay' => 15));
        }
    }
}
