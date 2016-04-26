<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class ManagerRegistryEventSubscriberFactory
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class ManagerRegistryEventSubscriberFactory
    implements FactoryInterface
{


    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ManagerRegistryEventSubscriber|mixed
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Utils\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ?  $serviceLocator->getServiceLocator() : $serviceLocator;

        /** @var ManagerRegistryProviderInterface $managerRegistryProvider */
        $managerRegistryProvider = $appServiceLocator->get(ManagerRegistryProviderInterface::class);
        $managerRegistry = $managerRegistryProvider->getManagerRegistry();


        return new ManagerRegistryEventSubscriber($managerRegistry);
    }
}
