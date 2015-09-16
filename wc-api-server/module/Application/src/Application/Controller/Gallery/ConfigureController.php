<?php
namespace Application\Controller\Gallery;

use Application\Service\Instagram\AuthenticationService;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class ConfigureController extends AbstractController
{
    /**
     * @var AuthenticationService
     */
    protected $authenticationService = null;

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        $data = $this->authenticationService->getAuthData();
        $viewModel->setVariable('user', $data->user);
        $viewModel->setVariable('configure', true);

        $viewModel->setTemplate( 'application/gallery/index' );
        $e->setResult($viewModel);
    }
}
