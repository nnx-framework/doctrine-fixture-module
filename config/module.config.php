<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\FixtureInitializer\ConnectionRegistryEventSubscriber;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ManagerRegistryEventSubscriber;


$config = [
    Module::CONFIG_KEY => [
        'fixturesLoaders'              => [

        ],
        'filters'                      => [

        ],
        'executors'                    => [

        ],
        'defaultFixtureEventListeners' => [
            ConnectionRegistryEventSubscriber::class => ConnectionRegistryEventSubscriber::class,
            ManagerRegistryEventSubscriber::class    => ManagerRegistryEventSubscriber::class
        ]
    ]
];

return array_merge_recursive(
    include __DIR__ . '/serviceManager.config.php',
    include __DIR__ . '/fixtureLoader.config.php',
    include __DIR__ . '/fixtureFilter.config.php',
    include __DIR__ . '/fixtureExecutor.config.php',
    include __DIR__ . '/doctrine.config.php',
    include __DIR__ . '/controllers.config.php',
    include __DIR__ . '/console.config.php',
    include __DIR__ . '/fixtureInitializer.config.php',
    $config
);