<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;


use Nnx\DoctrineFixtureModule\Entity\ExecutorController;
use Nnx\DoctrineFixtureModule\Entity\ExecutorControllerFactory;

return [
    'controllers' => [
        'factories' => [
            ExecutorController::class => ExecutorControllerFactory::class
        ]
    ],
];