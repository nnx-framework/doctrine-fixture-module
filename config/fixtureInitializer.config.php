<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManager;
use Nnx\DoctrineFixtureModule\FixtureInitializer;
return [
    FixtureInitializerManager::CONFIG_KEY => [
        'invokables'         => [

        ],
        'factories'          => [
            FixtureInitializer\ConnectionRegistryEventSubscriber::class => FixtureInitializer\ConnectionRegistryEventSubscriberFactory::class,
            FixtureInitializer\ManagerRegistryEventSubscriber::class => FixtureInitializer\ManagerRegistryEventSubscriberFactory::class,
        ],
        'abstract_factories' => [

        ],
        'shared'             => [
            FixtureInitializer\ConnectionRegistryEventSubscriber::class => true,
            FixtureInitializer\ManagerRegistryEventSubscriber::class => true,
        ]
    ],
];


