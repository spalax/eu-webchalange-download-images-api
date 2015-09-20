<?php
namespace Frontend\Controller;

use Frontend\API\Rest\Resources\PagesResource;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\Paginator\Adapter\Callback as CallbackAdapter;

class IndexController extends AbstractController
{
    /**
     * @var PagesResource
     */
    protected $pagesResource;

    /**
     * @param PagesResource $pagesResource
     */
    public function __construct(PagesResource $pagesResource)
    {
        $this->pagesResource = $pagesResource;
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        $data = $this->pagesResource->getCollection($this->params('page'), 10);

        $paginator = new ZendPaginator(new CallbackAdapter(function () use ($data){
            return $data->data;
        }, function () use ($data) {return $data->total_items;}));

        $paginator->setCurrentPageNumber($data->page)
                  ->setItemCountPerPage($data->page_size);

        $viewModel->setVariable('data', $data->data);
        $viewModel->setVariable('paginator', $paginator);
        $viewModel->setTemplate( 'frontend/index' );


        $e->setResult($viewModel);
    }
}
