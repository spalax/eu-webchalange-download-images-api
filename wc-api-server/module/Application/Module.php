<?php
namespace Application;

//use Zend\Mvc\MvcEvent;
use ZF\Apigility\Provider\ApigilityProviderInterface;

class Module implements ApigilityProviderInterface
{
    /**
     * @param MvcEvent $e
     */
//    public function onBootstrap(MvcEvent $e)
//    {
//        $gsm = $e->getApplication()->getEventManager()->getSharedManager();
//        $gsm->attach('ZF\Hal\Plugin\Hal', 'renderCollection.post', function(\Zend\EventManager\Event $e) {
//            /* @var $payload \ArrayObject */
//            $payload = $e->getParam('payload');
//            if ($payload->offsetExists('total_items')) {
//                $payload['total'] = $payload->offsetGet('total_items');
//            }
//        });
//    }

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
