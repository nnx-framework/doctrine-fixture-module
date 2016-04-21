<?php

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Nnx\DoctrineFixtureModule\Module;
use Nnx\ModuleOptions\Module as ModuleOptions;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp;
use Nnx\Doctrine\Module as DoctrineModule;
use Nnx\ZF2TestToolkit\Listener\InitTestAppListener;
use Nnx\ZF2TestToolkit\Listener\StopDoctrineLoadCliPostEventListener;

return [
    'modules'                 => [
        'DoctrineModule',
        'DoctrineORMModule',
        ModuleOptions::MODULE_NAME,
        DoctrineModule::MODULE_NAME,
        Module::MODULE_NAME,

        FixtureTestApp\TestModule1\Module::MODULE_NAME,
    ],
    'module_listener_options' => [
        'module_paths'      => [
            Module::MODULE_NAME => TestPaths::getPathToModule(),
            FixtureTestApp\TestModule1\Module::MODULE_NAME => __DIR__ . '/../module/TestModule1'
        ],
        'config_glob_paths' => [
            __DIR__ . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ],
    'service_manager'         => [
        'invokables' => [
            InitTestAppListener::class => InitTestAppListener::class,
            StopDoctrineLoadCliPostEventListener::class => StopDoctrineLoadCliPostEventListener::class
        ]
    ],
    'listeners'               => [
        InitTestAppListener::class,
        StopDoctrineLoadCliPostEventListener::class
    ]
];
