<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;


$config = [
    Module::CONFIG_KEY => [

    ]
];

return array_merge_recursive(
    include __DIR__ . '/serviceManager.config.php',
    $config
);