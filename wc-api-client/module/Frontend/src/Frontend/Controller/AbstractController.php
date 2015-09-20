<?php
namespace Frontend\Controller;

use Frontend\Service\Instagram\AuthorizationService;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\Controller\AbstractController as ZendAbstractController;

abstract class AbstractController extends ZendAbstractController
{
    /* (non-PHPdoc)
    * @see \Zend\Mvc\Controller\AbstractController::attachDefaultListeners()
    */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();

        $this->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function (MvcEvent $event) {
            $event->getTarget()->layout('layout/frontend/common');
        });
    }
}
