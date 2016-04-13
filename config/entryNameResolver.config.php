<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver;


return [
    EntryNameResolverManager::CONFIG_KEY => [
        'invokables'         => [
            ResolverByClassName::class => ResolverByClassName::class
        ],
        'factories'          => [
            EntryNameResolverChain::class            => EntryNameResolverChainFactory::class,
            ResolverByMap::class                     => ResolverByMapFactory::class
        ],
        'abstract_factories' => [

        ]
    ],
];


