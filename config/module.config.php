<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\FixtureInitializer\ConnectionRegistryEventSubscriber;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ManagerRegistryEventSubscriber;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ObjectManagerNameInitializer;

$config = [
    Module::CONFIG_KEY => [
        /**
         * В данной секции описываются загрузчкики фикстур
         */
        'fixturesLoaders'              => [

        ],
        /**
         * Описание фильтров фикстур
         */
        'filters'                      => [

        ],
        /**
         * Описание компонентов отвечающих за запуск фикстур
         */
        'executors'                    => [

        ],
        /**
         * Описание инициалайзеров (используются для внедрения зависимостей), которые загружаются для каждого Executor'a
         */
        'fixtureInitializer' => [
            ConnectionRegistryEventSubscriber::class => ConnectionRegistryEventSubscriber::class,
            ManagerRegistryEventSubscriber::class    => ManagerRegistryEventSubscriber::class
        ],
        /**
         * Инициалайзеры, создаваемые заново перед каждым запуском фикстур. При создание этих инициайзеров, им передаются
         * данные контекста
         */
        'contextInitializer' => [
            ObjectManagerNameInitializer::class => ObjectManagerNameInitializer::class
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