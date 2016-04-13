<?php

use Nnx\EntryNameResolver\PhpUnit\TestData\TestPaths;
use Nnx\EntryNameResolver\PhpUnit\TestData\ContextResolver\Service;
use Nnx\EntryNameResolver\PhpUnit\TestData\ContextResolver\Custom\Service as CustomService;
use Nnx\EntryNameResolver\Module;
use Nnx\ModuleOptions\Module as ModuleOptions;
return [
    'modules'                 => [
        ModuleOptions::MODULE_NAME,

        Module::MODULE_NAME,

        Service\Module1\Module::MODULE_NAME,
        Service\Module2\Module::MODULE_NAME,
        Service\Module3\Module::MODULE_NAME,
        Service\Service\Module::MODULE_NAME,

        CustomService\Module1\Module::MODULE_NAME,
        CustomService\Module2\Module::MODULE_NAME,
        CustomService\Module3\Module::MODULE_NAME,
        CustomService\Service\Module::MODULE_NAME,

    ],
    'module_listener_options' => [
        'module_paths'      => [
            ModuleOptions::MODULE_NAME,

            Module::MODULE_NAME => TestPaths::getPathToModule(),

            Service\Module1\Module::MODULE_NAME => TestPaths::getPathToContextResolverTestServiceDir() . 'module1',
            Service\Module2\Module::MODULE_NAME => TestPaths::getPathToContextResolverTestServiceDir() . 'module2',
            Service\Module3\Module::MODULE_NAME => TestPaths::getPathToContextResolverTestServiceDir() . 'module3',
            Service\Service\Module::MODULE_NAME => TestPaths::getPathToContextResolverTestServiceDir() . 'service',

            CustomService\Module1\Module::MODULE_NAME => TestPaths::getPathToContextResolverTestCustomServiceDir(). 'module1',
            CustomService\Module2\Module::MODULE_NAME => TestPaths::getPathToContextResolverTestCustomServiceDir() . 'module2',
            CustomService\Module3\Module::MODULE_NAME => TestPaths::getPathToContextResolverTestCustomServiceDir() . 'module3',
            CustomService\Service\Module::MODULE_NAME => TestPaths::getPathToContextResolverTestCustomServiceDir() . 'service',
        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];
