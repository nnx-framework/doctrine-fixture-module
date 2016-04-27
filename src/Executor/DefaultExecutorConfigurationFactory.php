<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManagerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;


/**
 * Class DefaultExecutorConfigurationFactory
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
class DefaultExecutorConfigurationFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;

        $defaultExecutorConfiguration = new DefaultExecutorConfiguration();

        $eventManager = $defaultExecutorConfiguration->getEventManager();

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $serviceLocator->get(ModuleOptionsPluginManagerInterface::class);
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        $defaultFixtureEventListeners = $moduleOptions->getFixtureInitializer();

        /** @var FixtureInitializerManagerInterface $fixtureInitializerManager */
        $fixtureInitializerManager = $appServiceLocator->get(FixtureInitializerManagerInterface::class);

        foreach ($defaultFixtureEventListeners as $listenerName) {
            $listener = $fixtureInitializerManager->get($listenerName);
            $eventManager->addEventSubscriber($listener);
        }

        return $defaultExecutorConfiguration;
    }
}
