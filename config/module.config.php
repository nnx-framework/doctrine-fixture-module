<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Doctrine\Fixture\Persistence\ConnectionRegistryEventSubscriber;
use Doctrine\Fixture\Persistence\ManagerRegistryEventSubscriber;


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
    $config
);