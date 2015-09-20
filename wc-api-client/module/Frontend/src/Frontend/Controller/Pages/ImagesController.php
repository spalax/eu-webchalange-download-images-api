<?php
namespace Frontend\Controller\Pages;

use Frontend\API\Rest\Resources\ImagesResource;
use Frontend\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Adapter\Callback as CallbackAdapter;

class ImagesController extends AbstractController
{
    /**
     * @var ImagesResource
     */
    protected $imagesResource;

    /**
     * @param ImagesResource $imagesResource
     */
    public function __construct(ImagesResource $imagesResource)
    {
        $this->imagesResource = $imagesResource;
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        $pageId = $this->params()->fromRoute('page_id');

        $data = $this->imagesResource->getCollection($pageId, $this->params('page'), 10);
        $paginator = new ZendPaginator(new CallbackAdapter(function () use ($data) {
            return $data->data;
        }, function () use ($data) {return $data->total_items;}));

        $paginator->setCurrentPageNumber($data->page)
                  ->setItemCountPerPage($data->page_size);


        $viewModel->setVariable('id', $pageId);
        $viewModel->setVariable('data', $data->data);
        $viewModel->setVariable('paginator', $paginator);

        $viewModel->setTemplate('frontend/images/list');

        return $e->setResult($viewModel);
    }
}
