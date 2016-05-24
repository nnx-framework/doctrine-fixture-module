<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FixtureDataReaderManageInitializer
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class FixtureDataReaderManageInitializerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $fixtureDataReaderManager = $appServiceLocator->get(FixtureDataReaderManagerInterface::class);

        return new FixtureDataReaderManageInitializer($fixtureDataReaderManager);
    }
}
