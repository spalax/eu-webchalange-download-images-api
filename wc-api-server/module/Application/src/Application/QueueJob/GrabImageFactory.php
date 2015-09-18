<?php
namespace Application\QueueJob;

use Doctrine\ORM\EntityManager;
use Zend\Dom\Document;
use Zend\Http\Client;
use Application\Service\Storage\StorageFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GrabImageFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ParseHtml
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        try {
            return new GrabImage( $serviceLocator->get( Client::class ),
                $serviceLocator->get( EntityManager::class ),
                $serviceLocator->get( StorageFactory::class )
                               ->createService( $serviceLocator ) );
        } catch (\Exception $e) {
            \Application\Stdlib\Debug\Utility::dump( $e->getMessage() );
        }
    }
}
