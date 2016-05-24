<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\FixtureInitializer\ConnectionRegistryEventSubscriber;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ManagerRegistryEventSubscriber;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ObjectManagerNameInitializer;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ResourceLoaderInitializer;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureDataReaderManageInitializer;

return [
    Module::CONFIG_KEY => [
        /**
         * В данной секции описываются загрузчкики фикстур
         */
        'fixturesLoaders' => [

        ],
        /**
         * Описание фильтров фикстур
         */
        'filters'         => [

        ],
        /**
         * Описание компонентов отвечающих за запуск фикстур
         */
        'executors'       => [

        ],

        /**
         * Ключем является имя класса фикстуры, а значением, конфиг описыайющи загрузчик ресурсов
         *
         * Пример:
         *
         * MyFixtureClass::class => [
         *      'name' => MyFixtureResourceLoader::class,
         *      'options' => [
         *          'key1' => 'value1'
         *      ]
         * ]
         *
         */
        'resourceLoader'     => [

        ],

        /**
         * Описание инициалайзеров (используются для внедрения зависимостей), которые загружаются для каждого Executor'a
         */
        'fixtureInitializer' => [
            ConnectionRegistryEventSubscriber::class => ConnectionRegistryEventSubscriber::class,
            ManagerRegistryEventSubscriber::class    => ManagerRegistryEventSubscriber::class,
            FixtureDataReaderManageInitializer::class => FixtureDataReaderManageInitializer::class
        ],

        /**
         * Инициалайзеры, создаваемые заново перед каждым запуском фикстур. При создание этих инициайзеров, им передаются
         * данные контекста
         */
        'contextInitializer' => [
            ObjectManagerNameInitializer::class => ObjectManagerNameInitializer::class,
            ResourceLoaderInitializer::class    => ResourceLoaderInitializer::class
        ]
    ]
];

