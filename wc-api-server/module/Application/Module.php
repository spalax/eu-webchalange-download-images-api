<?php
namespace Application;

//use Zend\Mvc\MvcEvent;
use Application\V1\Entity\Pages;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event as EventManagerEvent;
use ZF\Apigility\Provider\ApigilityProviderInterface;
use ZF\Hal\Link\Link as HalLink;

class Module implements ApigilityProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()
          ->getEventManager()
          ->getSharedManager()
          ->attach('ZF\Hal\Plugin\Hal', 'renderEntity', array($this, 'onRenderEntity'));
    }

    public function onRenderEntity(EventManagerEvent $e)
    {
        $entity = $e->getParam('entity');
        if (! $entity->entity instanceof Pages) {
            return;
        }

        $entity->getLinks()->add(HalLink::factory(array(
            'rel' => 'images',
            'route' => array(
                'name' => 'application.rest.images',
                'params' => ['page_id' => $entity->entity->getUuid()]
            ),
        )));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }
}
