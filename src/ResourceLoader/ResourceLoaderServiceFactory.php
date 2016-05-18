<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\ResourceLoader;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;

/**
 * Class ResourceLoaderServiceFactory
 *
 * @package Nnx\DoctrineFixtureModule\ResourceLoader
 */
class ResourceLoaderServiceFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return ResourceLoaderService
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        /** @var ResourceLoaderManagerInterface $resourceLoaderManager */
        $resourceLoaderManager = $appServiceLocator->get(ResourceLoaderManagerInterface::class);

        $resourceLoaderService = new ResourceLoaderService($resourceLoaderManager);

        $resourceLoaderService->setClassFixtureToResourceLoader($moduleOptions->getResourceLoader());

        return $resourceLoaderService;
    }
}
