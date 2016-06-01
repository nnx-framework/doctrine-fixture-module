<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;
use Interop\Container\ContainerInterface;

/**
 * Class SimpleFixtureImportEngineFactory
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
class SimpleFixtureImportEngineFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $serviceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        $simpleFixtureEntityLocatorServiceName = $moduleOptions->getSimpleFixtureEntityLocator();

        /** @var ContainerInterface $simpleFixtureEntityLocator */
        $simpleFixtureEntityLocator = $serviceLocator->get($simpleFixtureEntityLocatorServiceName);

        return new SimpleFixtureImportEngine($simpleFixtureEntityLocator);
    }
}
