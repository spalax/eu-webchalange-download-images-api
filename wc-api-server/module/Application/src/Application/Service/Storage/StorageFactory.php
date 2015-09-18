<?php
namespace Application\Service\Storage;

use AwsModule\Factory\AwsFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StorageFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return StorageInterface
     */
    public function createService( ServiceLocatorInterface $serviceLocator )
    {
        $appConfig = $serviceLocator->get('Config')['application'];

        if (!array_key_exists('imageStorage', $appConfig)) {
            throw new \DomainException('imageStorage config must be defined in application module.config.php');
        }

        $config = $appConfig['imageStorage'];

        if (!array_key_exists('type', $config)) {
            throw new \DomainException('Storage config, type must be defined');
        }
        if (!array_key_exists('options', $config)) {
            throw new \DomainException('Storage config, options must be defined if storage type is "local"');
        }
        
        if ($config['type'] == 'aws') {
            $awsSdk = $serviceLocator->get( AwsFactory::class )
                                     ->createService($serviceLocator);

            return new AwsS3Storage($awsSdk->createS3(), $config['options']);
        } else {
            return new LocalStorage($config['options']);
        }
    }
}
