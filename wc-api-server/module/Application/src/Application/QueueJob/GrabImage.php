<?php
namespace Application\QueueJob;

use Application\Service\Storage\StorageInterface;
use Application\V1\Entity\Images;
use Application\V1\Entity\PageInterface;
use Aws;
use Doctrine\ORM\EntityManagerInterface;
use SlmQueue\Job\AbstractJob;
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

    public function execute()
    {
        try {
            $this->entityManager->beginTransaction();
            $payload = $this->getContent();

            echo "processing >> " . $payload['image_src'] .
                 " >> for id >> " . $payload['page_id'] .
                 " >> for ext >> " . $payload['image_ext'] . "\n";

            $this->httpClient->setUri( $payload['image_src'] );

            $response  = $this->httpClient->send();
            if ($payload['image_ext'] == 'svg') {
                $xmlget = simplexml_load_string($response->getBody());
                $xmlattributes = $xmlget->attributes();
                $width = preg_replace('/[a-z]/', '', strtolower((string) $xmlattributes->width));
                $height = preg_replace('/[a-z]/', '', strtolower((string) $xmlattributes->height));
                $imageInfo = [$width, $height, 'mime'=>$response->getHeaders()->get('Content-Type')->getFieldValue()];
                $imageSize = mb_strlen($response->getBody(), '8bit');
            } else {
                $imageInfo = getimagesizefromstring( $response->getBody() );
                $contentLength = $response->getHeaders()
                                          ->get( 'Content-Length' );
                if ($contentLength === false) {
                    $imageSize = mb_strlen($response->getBody(), $imageInfo['bits'].'bit');
                } else {
                    $imageSize = $contentLength->getFieldValue();
                }
            }

            $url = $this->storageService
                        ->store( $payload['image_ext'], $response->getBody() );

            /* @var $pageEntity \Application\V1\Entity\Pages */
            $pageEntity = $this->entityManager->find( 'Application\V1\Entity\Pages', $payload['page_id'] );

            $imageEntity = new Images();
            $imageEntity->setContentType( $imageInfo['mime'] );
            $imageEntity->setWidth( $imageInfo[0] );
            $imageEntity->setHeight( $imageInfo[1] );
            $imageEntity->setSize( $imageSize );
            $imageEntity->setLocalPath( $url );
            $imageEntity->setRemotePath( $payload['image_src'] );
            $imageEntity->setPage( $pageEntity );

            $imagesCnt = $pageEntity->getPendingImagesCnt();

            if ( $imagesCnt > 0 ) {
                $pageEntity->setPendingImagesCnt( $imagesCnt - 1 );
                if ($pageEntity->getPendingImagesCnt() == 0) {
                    $pageEntity->setStatus(PageInterface::STATUS_DONE);
                }
            }

            $this->entityManager->persist( $imageEntity );
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            \Application\Stdlib\Debug\Utility::dump( $e->getMessage() );
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
