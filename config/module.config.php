<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver;


$config = [
    Module::CONFIG_KEY => [

    ]
];

return array_merge_recursive(
    include __DIR__ . '/entryNameResolver.config.php',
    include __DIR__ . '/serviceManager.config.php',
    $config
);