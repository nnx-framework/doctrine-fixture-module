<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\Module as TestModule1;

return [
    'doctrine' => [
        'driver' => [
            'test' => [
                'drivers' => [
                    TestModule1::MODULE_NAME . '\\Entity' => TestModule1::MODULE_NAME,
                ]
            ],
            TestModule1::MODULE_NAME => [
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'],
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            ]
        ]
    ]
];