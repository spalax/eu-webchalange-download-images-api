<?php
namespace Application\V1\Rest\Pages;

use Application\V1\Entity\PageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Rhumsaa\Uuid\Uuid;
use SlmQueue\Job\JobPluginManager as QueueJobPluginManager;
use SlmQueue\Queue\QueueInterface;
use ZF\ApiProblem\ApiProblem;
use Application\V1\Entity\Pages as PagesEntity;
use ZF\Rest\AbstractResourceListener;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;

class PagesResource extends AbstractResourceListener
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var QueueJobPluginManager
     */
    protected $queueJobPluginManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param QueueInterface $queue
     * @param QueueJobPluginManager $queueJobPluginManager
     */
    public function __construct(EntityManagerInterface $entityManager,
                                QueueInterface $queue)
    {
        $this->entityManager = $entityManager;
        $this->queue = $queue;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        try {
            $pageEntity = new PagesEntity();

            $pageEntity->setUuid(Uuid::uuid4()->toString());
            $pageEntity->setUrl($data->site_url);
            $pageEntity->setStatus(PageInterface::STATUS_PENDING);

            $this->entityManager->persist($pageEntity);
            $this->entityManager->flush();

            $queueJob = $this->queue->getJobPluginManager()->get('Application\QueueJob\ParsePage');
            $queueJob->setContent(array('page_url'=>$pageEntity->getUrl(),
                                        'page_id'=>$pageEntity->getId()));

            $this->queue->push($queueJob);

            return array('id'=>$pageEntity->getUuid(),
                         'status'=>$this->getPageStatus($pageEntity));

        } catch (\Exception $e) {
            return new ApiProblem(500, $e->getMessage());
        }
    }

    /**
     * @param PagesEntity $pagesEntity
     *
     * @return string
     */
    protected function getPageStatus(PagesEntity $pagesEntity)
    {
        switch ($pagesEntity->getStatus()) {
            case PageInterface::STATUS_PENDING:
                return 'pending';
            case PageInterface::STATUS_RUNNING:
                return 'running';
            case PageInterface::STATUS_BURIED:
                return 'buried';
            default:
                return 'done';
        }
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        try {
            if (!Uuid::isValid($id)) {
                return new ApiProblem(400, 'Invalid identifier');
            }

            $repository = $this->entityManager->getRepository('Application\V1\Entity\Pages');

            /* @var $pagesEntity PagesEntity */
            $pagesEntity = $repository->findOneByUuid($id);

            if (is_null($pagesEntity)) {
                return new ApiProblem(404, 'Requested site not found');
            }

            return array('id'=>$id,
                         'status'=>$this->getPageStatus($pagesEntity));
        } catch (\Exception $e) {
            return new ApiProblem(500, $e->getMessage());
        }
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        $qb = $this->entityManager
                   ->getRepository('Application\V1\Entity\Pages')
                   ->createQueryBuilder('p');

        return new Paginator(new DoctrineAdapter(new DoctrinePaginator($qb->getQuery())));
    }
}
