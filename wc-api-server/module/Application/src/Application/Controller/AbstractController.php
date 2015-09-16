<?php
namespace Application\Controller;

use Application\Service\Instagram\AuthorizationService;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\Controller\AbstractController as ZendAbstractController;

abstract class AbstractController extends ZendAbstractController
{
    /**
     * @var AuthorizationService
     */
    protected $authorizationService = null;

    /**
     * @param AuthorizationService $authorizationService
     */
    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /* (non-PHPdoc)
     * @see \Zend\Mvc\Controller\AbstractController::dispatch()
     */
    public function dispatch(Request $request, Response $response = null)
    {
        if (!is_null($this->serviceLocator
                          ->get('di')
                          ->get('Application\Service\Instagram\AuthenticationService')
                          ->getAuthData())) {
            return $this->redirect()->toRoute( 'application/gallery/configure' );
        }
        return parent::dispatch($request, $response);
    }

    /* (non-PHPdoc)
    * @see \Zend\Mvc\Controller\AbstractController::attachDefaultListeners()
    */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();

        $this->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function (MvcEvent $event) {
            $event->getTarget()->layout('layout/application/common');
        });
    }
}
