<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureServiceInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SimpleFixtureServiceInitializerFactory
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class SimpleFixtureInitializerFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        /** @var SimpleFixtureServiceInterface $simpleFixtureService */
        $simpleFixtureService = $appServiceLocator->get(SimpleFixtureServiceInterface::class);

        return new SimpleFixtureInitializer($simpleFixtureService);
    }
}
