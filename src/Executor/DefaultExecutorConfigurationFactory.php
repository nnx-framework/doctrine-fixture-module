<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;
use Doctrine\Common\EventSubscriber;

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
        $defaultExecutorConfiguration = new DefaultExecutorConfiguration();

        $eventManager = $defaultExecutorConfiguration->getEventManager();

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $serviceLocator->get(ModuleOptionsPluginManagerInterface::class);
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        $defaultFixtureEventListeners = $moduleOptions->getDefaultFixtureEventListeners();

        foreach ($defaultFixtureEventListeners as $listenerName) {
            /** @var EventSubscriber $listener */
            $listener = $serviceLocator->get($listenerName);
            $eventManager->addEventSubscriber($listener);
        }

        return $defaultExecutorConfiguration;
    }
}
