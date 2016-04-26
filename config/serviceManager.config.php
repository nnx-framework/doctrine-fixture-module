<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\Executor\DefaultExecutorConfigurationFactory;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManager;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManager;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManager;
use Nnx\DoctrineFixtureModule\Executor\DefaultExecutorConfiguration;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorBuilderInterface;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorBuilderFactory;
use Nnx\DoctrineFixtureModule\FilterUsedFixtureService\FilterUsedFixtureListenerFactory;
use Nnx\DoctrineFixtureModule\FilterUsedFixtureService\FilterUsedFixtureListener;
use Nnx\DoctrineFixtureModule\FilterUsedFixtureService\FilterUsedFixtureServiceInterface;
use Nnx\DoctrineFixtureModule\FilterUsedFixtureService\FilterUsedFixtureServiceFactory;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProvider;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManagerInterface;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManager;

return [
    'service_manager' => [
        'invokables'         => [
            FixtureLoaderManagerInterface::class      => FixtureLoaderManager::class,
            FixtureFilterManagerInterface::class      => FixtureFilterManager::class,
            FixtureExecutorManagerInterface::class    => FixtureExecutorManager::class,
            ManagerRegistryProviderInterface::class   => ManagerRegistryProvider::class,
            FixtureInitializerManagerInterface::class => FixtureInitializerManager::class
        ],
        'factories'          => [
            DefaultExecutorConfiguration::class      => DefaultExecutorConfigurationFactory::class,
            FixtureExecutorBuilderInterface::class   => FixtureExecutorBuilderFactory::class,
            FilterUsedFixtureListener::class         => FilterUsedFixtureListenerFactory::class,
            FilterUsedFixtureServiceInterface::class => FilterUsedFixtureServiceFactory::class
        ],
        'abstract_factories' => [

        ],
        'shared'             => [
            DefaultExecutorConfiguration::class => false
        ]
    ],
];


