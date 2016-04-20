<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp;

use Nnx\DoctrineFixtureModule\Module;
use Doctrine\Fixture\Loader\ClassLoader;
use Doctrine\Fixture\Loader\DirectoryLoader;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1;
use Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture;

return [
    Module::CONFIG_KEY => [
        'fixturesLoaders' => [
            'testChainFixtureLoader' => [
                [
                    'name' => ClassLoader::class,
                    'options' => [
                        'classList' => [
                            TestModule1\FooFixture::class,
                            TestModule1\BarFixture::class,
                        ]
                    ]
                ],
                [
                    'name' => DirectoryLoader::class,
                    'options' => [
                        'directory' => __DIR__ . '/../../fixtures'
                    ]
                ],
                [
                    'name' => 'childTestChain',
                ]
            ],
            'childTestChain' => [
                [
                    'name' => ClassLoader::class,
                    'options' => [
                        'classList' => [
                            TestModule1\BazFixture::class,
                        ]
                    ]
                ]
            ]
        ],
        'filters' => [
            'testChainFixtureFilter' => [
                [
                    'name' => 'childTestChainFixtureFilter'
                ]
            ],
            'childTestChainFixtureFilter' => [

            ],
            'testFilterUsedFixture' => [
                [
                    'name' => FilterUsedFixture::class
                ]
            ]
        ],
        'executors' => [
            'testExecutor' => [
                'fixturesLoader' => 'testChainFixtureLoader',
                'filter' => 'testChainFixtureFilter'
            ],
            'testFilterUsedFixture' => [
                'fixturesLoader' => 'testChainFixtureLoader',
                'filter' => 'testFilterUsedFixture'
            ]
        ]

    ]
];