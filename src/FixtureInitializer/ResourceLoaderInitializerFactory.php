<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderServiceInterface;

/**
 * Class ResourceLoaderInitializerFactory
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class ResourceLoaderInitializerFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $context = $this->getCreationOptions();


        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        /** @var ResourceLoaderServiceInterface $resourceLoaderService */
        $resourceLoaderService = $appServiceLocator->get(ResourceLoaderServiceInterface::class);

        $resourceLoaderInitializer = new ResourceLoaderInitializer($resourceLoaderService);
        $resourceLoaderInitializer->setContextData($context);

        return $resourceLoaderInitializer;
    }
}
