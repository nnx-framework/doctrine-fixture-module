<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\Executor\ClassListFixtureExecutor;
use Nnx\DoctrineFixtureModule\Executor\ClassListFixtureExecutorFactory;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManager;
use Nnx\DoctrineFixtureModule\Executor\Executor;
use Nnx\DoctrineFixtureModule\Executor\ExecutorFactory;
use Nnx\DoctrineFixtureModule\Executor\AbstractExecutorFactory;

return [
    FixtureExecutorManager::CONFIG_KEY => [
        'invokables'         => [

        ],
        'factories'          => [
            Executor::class                 => ExecutorFactory::class,
            ClassListFixtureExecutor::class => ClassListFixtureExecutorFactory::class

        ],
        'abstract_factories' => [
            AbstractExecutorFactory::class => AbstractExecutorFactory::class
        ],
        'shared'             => [
            Executor::class => false
        ]
    ],
];


