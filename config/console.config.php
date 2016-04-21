<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\Entity\ExecutorController;

return [
    'console' => [
        'router' => [
            'routes' => [
                'doctrine-execute-fixture' => [
                    'options' => [
                        'route'    => 'nnx:fixture execute-fixture --fixtureClassName',
                        'defaults' => [
                            'controller' => ExecutorController::class,
                            'action'     => 'executeFixture'
                        ]
                    ],
                ],
                'doctrine-run-executor' => [
                    'options' => [
                        'route'    => 'nnx:fixture run-executor --executorName',
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