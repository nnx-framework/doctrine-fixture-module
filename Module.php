<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\FilterUsedFixtureService\FilterUsedFixtureListener;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManager;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManagerInterface;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerProviderInterface;
use Nnx\ModuleOptions\ModuleConfigKeyProviderInterface;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManager;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderProviderInterface;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManager;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterProviderInterface;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManager;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorProviderInterface;
use Nnx\ModuleOptions\Module as ModuleOptions;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;

/**
 * Class Module
 *
 * @package Nnx\DoctrineFixtureModule
 */
class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface,
    InitProviderInterface,
    ConfigProviderInterface,
    ModuleConfigKeyProviderInterface,
    DependencyIndicatorInterface,
    ConsoleUsageProviderInterface,
    ConsoleBannerProviderInterface
{
    /**
     * Имя секции в конфиги приложения отвечающей за настройки модуля
     *
     * @var string
     */
    const CONFIG_KEY = 'nnx_doctrine_fixture_module';

    /**
     * Имя модуля
     *
     * @var string
     */
    const MODULE_NAME = __NAMESPACE__;

    /**
     * @inheritDoc
     */
    public function getModuleDependencies()
    {
        return [
            ModuleOptions::MODULE_NAME
        ];
    }


    /**
     * @inheritDoc
     */
    public function getModuleConfigKey()
    {
        return static::CONFIG_KEY;
    }

    /**
     * @param ModuleManagerInterface $manager
     *
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!$manager instanceof ModuleManager) {
            $errMsg =sprintf('Module manager not implement %s', ModuleManager::class);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        /** @var ServiceLocatorInterface $sm */
        $sm = $manager->getEvent()->getParam('ServiceManager');

        if (!$sm instanceof ServiceLocatorInterface) {
            $errMsg = sprintf('Service locator not implement %s', ServiceLocatorInterface::class);
            throw new Exception\InvalidArgumentException($errMsg);
        }
        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $sm->get('ServiceListener');
        if (!$serviceListener instanceof ServiceListenerInterface) {
            $errMsg = sprintf('ServiceListener not implement %s', ServiceListenerInterface::class);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $serviceListener->addServiceManager(
            FixtureLoaderManagerInterface::class,
            FixtureLoaderManager::CONFIG_KEY,
            FixtureLoaderProviderInterface::class,
            'getFixtureLoaderConfig'
        );

        $serviceListener->addServiceManager(
            FixtureFilterManagerInterface::class,
            FixtureFilterManager::CONFIG_KEY,
            FixtureFilterProviderInterface::class,
            'getFixtureFilterConfig'
        );

        $serviceListener->addServiceManager(
            FixtureExecutorManagerInterface::class,
            FixtureExecutorManager::CONFIG_KEY,
            FixtureExecutorProviderInterface::class,
            'getFixtureExecutorConfig'
        );

        $serviceListener->addServiceManager(
            FixtureInitializerManagerInterface::class,
            FixtureInitializerManager::CONFIG_KEY,
            FixtureInitializerProviderInterface::class,
            'getFixtureInitializerConfig'
        );
    }

    /**
     * @inheritdoc
     *
     * @param EventInterface $e
     *
     * @return array|void
     * @throws \Nnx\DoctrineFixtureModule\Exception\InvalidArgumentException
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function onBootstrap(EventInterface $e)
    {
        if (!$e instanceof MvcEvent) {
            $errMsg =sprintf('Event not implement %s', MvcEvent::class);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $app = $e->getApplication();

        $eventManager        = $app->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sl = $app->getServiceManager();

        /** @var  FilterUsedFixtureListener$filterUsedFixtureListener */
        $filterUsedFixtureListener = $sl->get(FilterUsedFixtureListener::class);
        $filterUsedFixtureListener->attach($eventManager);


    }


    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/',
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @inheritDoc
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'Doctrine data fixture',
            'nnx:fixture execute-fixture --fixtureClassName=' => 'Run fixture bu class name',
            ['--fixtureClassName=FIXTURE_CLASS_NAME', 'Fixture class name'],
            'nnx:fixture run-executor --executorName=' => 'Run fixture executor by name',
            ['--executorName==EXECUTOR_NAME', 'Executor name'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getConsoleBanner(AdapterInterface $console)
    {
        return 'Doctrine fixture tools';
    }


} 