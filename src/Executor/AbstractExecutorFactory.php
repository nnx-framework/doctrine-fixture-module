<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;


/**
 * Class AbstractExecutorFactory
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
class AbstractExecutorFactory implements AbstractFactoryInterface
{
    /**
     * Флаг определяет была ли инциализированна фабрика
     *
     * @var bool
     */
    protected $isInit = false;

    /**
     * Настройки модуля
     *
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * Инициализация фабрики
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    protected function init(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->isInit) {
            return null;
        }

        $appServiceLocator = $serviceLocator;
        if ($serviceLocator instanceof AbstractPluginManager) {
            $appServiceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);
        /** @var ModuleOptions $moduleOptions */
        $this->moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        $this->isInit = true;
    }


    /**
     * @inheritDoc
     * 
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $this->init($serviceLocator);

        return array_key_exists($requestedName, $this->moduleOptions->getExecutors());
    }

    /**
     * @inheritDoc
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $this->init($serviceLocator);

        $executors = $this->moduleOptions->getExecutors();

        $creationOptions = $executors[$requestedName];

        $creationOptions['name'] = $requestedName;

        /** @var FixtureExecutorManagerInterface  $serviceLocator*/
        return $serviceLocator->get(Executor::class, $creationOptions);
    }
}
