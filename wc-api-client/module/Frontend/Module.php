<?php
namespace Frontend;

use Frontend\Options\ModuleOptions;
use Zend\Mvc\MvcEvent;

class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        /* @var $sm \Zend\ServiceManager\ServiceManager */
        $sm = $e->getApplication()->getServiceManager();

        /* @var $config array */
        $config = $sm->get('config');

        $moduleOptions = new ModuleOptions( isset( $config['frontend'] ) ?
                                                $config['frontend'] :
                                                array() );

        /* @var $di \Zend\Di\Di */
        $di = $sm->get('di');
        $di->instanceManager()->addSharedInstance($moduleOptions,
                                                  'Frontend\Options\ModuleOptions');

        $di->instanceManager()->addSharedInstance($di, 'Zend\Di\Di');
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
