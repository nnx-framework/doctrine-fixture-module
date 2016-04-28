<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\Controller\ExecutorController;

return [
    'console' => [
        'router' => [
            'routes' => [
                'doctrine-execute-fixture' => [
                    'options' => [
                        'route'    => 'nnx:fixture (import|purge):method fixture <fixtureClassName> [--object-manager=]',
                        'defaults' => [
                            'controller' => ExecutorController::class,
                            'action'     => 'executeFixture'
                        ]
                    ],
                ],
                'doctrine-run-executor' => [
                    'options' => [
                        'route'    => 'nnx:fixture (import|purge):method executor <executorName> [--object-manager=]',
                        'defaults' => [
                            'controller' => ExecutorController::class,
                            'action'     => 'runExecutor'
                        ]
                    ],
                ]
            ]
        ]
    ],
];