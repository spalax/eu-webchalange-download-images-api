<?php
namespace Application\QueueJob;

use Doctrine\ORM\EntityManager;
use Zend\Dom\Document;
use Zend\Http\Client;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ParsePageFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ParsePage
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ParsePage($serviceLocator->get(Client::class),
                             $serviceLocator->get(EntityManager::class),
                             $serviceLocator->get(Document\Query::class),
                             $serviceLocator->get('SlmQueue\Queue\QueuePluginManager')
                                            ->get('GrabImageQueue'));
    }
}
