<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FilterUsedFixtureService;

use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class FilterUsedFixtureServiceFactory
 *
 * @package Nnx\DoctrineFixtureModule\FilterUsedFixtureService
 */
class FilterUsedFixtureServiceFactory implements FactoryInterface
{


    /**
     * @inheritDoc
     *
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Utils\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator;
        if ($serviceLocator instanceof AbstractPluginManager) {
            $appServiceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var ManagerRegistryProviderInterface $managerRegistryProvider */
        $managerRegistryProvider = $appServiceLocator->get(ManagerRegistryProviderInterface::class);

        return new FilterUsedFixtureService($managerRegistryProvider);
    }
}
