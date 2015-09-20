<?php
namespace Frontend\Controller;

use Zend\Mvc\MvcEvent;
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
