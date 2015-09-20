<?php
namespace Application\QueueJob;

use Application\V1\Entity\PageInterface;
use Doctrine\ORM\EntityManagerInterface;
use SlmQueue\Job\AbstractJob;
use SlmQueue\Queue\QueueAwareInterface;
use SlmQueue\Queue\QueueAwareTrait;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\WorkerEvent;
use SlmQueueDoctrine\Job\Exception\BuryableException;
use SlmQueueDoctrine\Job\Exception\ReleasableException;
use Zend\Dom\Document;
use Zend\Http\Client as HttpClient;

class ParsePage extends AbstractJob implements QueueAwareInterface
{
    use QueueAwareTrait;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Document\Query
     */
    protected $documentQuery;

    /**
     * @var QueueInterface
     */
    protected $grabImageQueue;

    /**
     * @param HttpClient $httpClient
     * @param EntityManagerInterface $entityManager
     * @param Document\Query $documentQuery
     * @param QueueInterface $grabImageQueue
     */
    public function __construct(HttpClient $httpClient,
                                EntityManagerInterface $entityManager,
                                Document\Query $documentQuery,
                                QueueInterface $grabImageQueue)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->documentQuery = $documentQuery;
        $this->grabImageQueue = $grabImageQueue;
    }

    /**
     * @param string $url
     * @param string $scheme
     * @param string $host
     *
     * @return string
     */
    protected function normalizeSchemeAndHost($url, $scheme, $host)
    {
        if (($_url = parse_url($url)) !== false){ // valid url
            if (empty($_url['scheme'])) {
                $newUrl = strtolower($scheme). "://";
            } else {
                $newUrl = strtolower($_url['scheme']) . "://";
            }

            if (!empty($_url['user']) && !empty($_url['pass'])) {
                $newUrl .= $_url['user'] . ":" . $_url['pass'] . "@";
            }

            if (empty($_url['host'])) {
                $newUrl .= strtolower($host);
            } else {
                $newUrl .= strtolower($_url['host']);
            }

            $newUrl .= '/'.ltrim($_url['path'], '/');
            if (!empty($_url['query'])) {
                $newUrl .= "?" . $_url['query'];
            }

            if (!empty($_url['fragment'])) {
                $newUrl .= "#" . $_url['fragment'];
            }
            return $newUrl;
        }
        return $url; // could return false if you'd like
    }

    public function execute()
    {
        $payload = $this->getContent();
        echo "processing >> " . $payload['page_url'] .
             " >> for id >> " . $payload['page_id'] . "\n";

        /* @var \Application\V1\Entity\Pages $pageEntity */
        $pageEntity = $this->entityManager
                           ->find('Application\V1\Entity\Pages',
                                $payload['page_id']);

        try {
            $this->httpClient->setUri( $payload['page_url'] );

            $response = $this->httpClient->send();

            $document = new Document( $response->getBody() );
            $manager = $this->grabImageQueue
                            ->getJobPluginManager();

            $jobs = [];
            $parsedPageUrl = parse_url($this->httpClient->getRequest()->getUriString());
            $cnt = 0;
            /* @var \DOMElement $node */
            foreach ( $this->documentQuery->execute( '//body//img', $document ) as $node ) {
                $job = $manager->get( 'Application\QueueJob\GrabImage' );
                $src = $this->normalizeSchemeAndHost($node->getAttribute( 'src' ),
                                                     $parsedPageUrl['scheme'],
                                                     $parsedPageUrl['host']);

                $ext = strtolower( pathinfo( $src, PATHINFO_EXTENSION ) );
                $job->setContent( [ 'image_src' => $src,
                                    'image_ext' => $ext,
                                    'page_id' => $payload['page_id'] ] );
                $jobs[]=$job;
                $cnt++;
            }

            if ($cnt < 1) {
                $pageEntity->setStatus(PageInterface::STATUS_DONE);
            } else {
                $pageEntity->setStatus(PageInterface::STATUS_RUNNING);
            }

            $pageEntity->setPendingImagesCnt($cnt);
            $pageEntity->setTotalImagesCnt($cnt);

            $this->entityManager->flush();

            foreach ($jobs as $job) {
                $this->grabImageQueue->push($job);
            }
            echo "Jobs to push >> " . count($jobs) . " count pending images >>" . $cnt . "\n";

        } catch (\Exception $e) {
            echo 'Exception: >> '.$e->getMessage();
            $pageEntity->setErrorMessage($e->getMessage());

            if ($pageEntity->getStatusNumeric() == PageInterface::STATUS_RECOVERING) {
                $pageEntity->setStatus(PageInterface::STATUS_ERROR);
                $this->entityManager->flush();
                return WorkerEvent::JOB_STATUS_FAILURE;
            } else {
                $pageEntity->setStatus(PageInterface::STATUS_RECOVERING);
                $this->entityManager->flush();
                throw new ReleasableException(array('priority' => 10, 'delay' => 15));
            }
        }
    }
}
