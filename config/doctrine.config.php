<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
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