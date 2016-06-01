<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\Executor\DefaultExecutorConfigurationFactory;
use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManager;
use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerInterface;
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
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderManagerInterface;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProvider;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManagerInterface;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManager;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderManager;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderServiceInterface;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderServiceFactory;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureServiceInterface;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureServiceFactory;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureMetadataBuilderInterface;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureMetadataBuilderFactory;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureImportEngineInterface;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureImportEngineFactory;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\EntityLocator;

return [
    'service_manager' => [
        'invokables'         => [
            FixtureLoaderManagerInterface::class      => FixtureLoaderManager::class,
            FixtureFilterManagerInterface::class      => FixtureFilterManager::class,
            FixtureExecutorManagerInterface::class    => FixtureExecutorManager::class,
            ManagerRegistryProviderInterface::class   => ManagerRegistryProvider::class,
            FixtureInitializerManagerInterface::class => FixtureInitializerManager::class,
            ResourceLoaderManagerInterface::class     => ResourceLoaderManager::class,
            FixtureDataReaderManagerInterface::class  => FixtureDataReaderManager::class,
            EntityLocator::class                      => EntityLocator::class
        ],
        'factories'          => [
            DefaultExecutorConfiguration::class          => DefaultExecutorConfigurationFactory::class,
            FixtureExecutorBuilderInterface::class       => FixtureExecutorBuilderFactory::class,
            FilterUsedFixtureListener::class             => FilterUsedFixtureListenerFactory::class,
            FilterUsedFixtureServiceInterface::class     => FilterUsedFixtureServiceFactory::class,
            ResourceLoaderServiceInterface::class        => ResourceLoaderServiceFactory::class,
            SimpleFixtureServiceInterface::class         => SimpleFixtureServiceFactory::class,
            SimpleFixtureMetadataBuilderInterface::class => SimpleFixtureMetadataBuilderFactory::class,
            SimpleFixtureImportEngineInterface::class    => SimpleFixtureImportEngineFactory::class
        ],
        'abstract_factories' => [

        ],
        'shared'             => [
            DefaultExecutorConfiguration::class => false
        ]
    ],
];


