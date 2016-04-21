<?php

use Nnx\DoctrineFixtureModule\Module;

return [
    'doctrine' => [
        'driver' => [
            Module::MODULE_NAME => [
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'],
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            ],
        ],
    ],
];