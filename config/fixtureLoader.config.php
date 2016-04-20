<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManager;
use Doctrine\Fixture\Loader\ChainLoader;
use Nnx\DoctrineFixtureModule\Loader\ChainLoaderFactory;
use Nnx\DoctrineFixtureModule\Loader\ClassLoaderFactory;
use Doctrine\Fixture\Loader\ClassLoader;
use Doctrine\Fixture\Loader\DirectoryLoader;
use Nnx\DoctrineFixtureModule\Loader\DirectoryLoaderFactory;
use Doctrine\Fixture\Loader\GlobLoader;
use Nnx\DoctrineFixtureModule\Loader\GlobLoaderFactory;
use Doctrine\Fixture\Loader\RecursiveDirectoryLoader;
use Nnx\DoctrineFixtureModule\Loader\RecursiveDirectoryLoaderFactory;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderAbstractFactory;

return [
    FixtureLoaderManager::CONFIG_KEY => [
        'invokables'         => [

        ],
        'factories'          => [
            ChainLoader::class              => ChainLoaderFactory::class,
            ClassLoader::class              => ClassLoaderFactory::class,
            DirectoryLoader::class          => DirectoryLoaderFactory::class,
            GlobLoader::class               => GlobLoaderFactory::class,
            RecursiveDirectoryLoader::class => RecursiveDirectoryLoaderFactory::class

        ],
        'abstract_factories' => [
            FixtureLoaderAbstractFactory::class => FixtureLoaderAbstractFactory::class
        ],
        'shared' => [

        ]
    ],
];


