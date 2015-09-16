<?php
namespace Application\Controller\Login;

use Application\Controller\AbstractController;
use Application\Service\Instagram\AuthenticationService;
use Application\Service\Instagram\AuthorizationService;
use Application\Service\Instagram\Exception\InvalidCodeException;
use Application\Validator\Instagram\CodeValidator;
use Application\Exception\DomainException;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class SuccessController extends AbstractController
{
    /**
     * @var CodeValidator
     */
    protected $validator = null;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService = null;

    /**
     * @param AuthenticationService $authenticationService
     * @param AuthorizationService $authorizationService
     * @param CodeValidator $validator
     */
    public function __construct(AuthenticationService $authenticationService,
                                AuthorizationService $authorizationService,
                                CodeValidator $validator)
    {
        $this->validator = $validator;
        $this->authenticationService = $authenticationService;

        parent::__construct($authorizationService);
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        try {
            $this->authenticationService->authenticate($this->params()->fromQuery( 'code', null ));
            return $this->redirect()->toRoute('application/gallery/configure');
        } catch ( InvalidCodeException $invalidCodeException ) {
            $viewModel->setTemplate( 'application/error/unrecoverable' );
            $viewModel->setVariable( 'errorMessage', 'Incorrect code string returned from Instagram' );
        } catch ( DomainException $domainException) {
            $viewModel->setTemplate( 'application/error/unrecoverable' );
        }

        return $e->setResult($viewModel);
    }
}
