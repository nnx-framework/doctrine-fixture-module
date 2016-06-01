<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SimpleFixtureOrmServiceFactory
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
class SimpleFixtureServiceFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var SimpleFixtureMetadataBuilderInterface $simpleFixtureMetadataBuilder */
        $simpleFixtureMetadataBuilder = $serviceLocator->get(SimpleFixtureMetadataBuilderInterface::class);

        /** @var SimpleFixtureImportEngineInterface $simpleFixtureImportEngine */
        $simpleFixtureImportEngine = $serviceLocator->get(SimpleFixtureImportEngineInterface::class);
        return new SimpleFixtureService($simpleFixtureMetadataBuilder, $simpleFixtureImportEngine);
    }
}
