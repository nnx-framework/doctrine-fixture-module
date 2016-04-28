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
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;

return [
    'doctrine' => [
        'entitymanager' => [
            'test' => [
                'configuration' => 'test',
                'connection'    => 'test',
            ]
        ],
        'connection' => [
            'test' => [
                'configuration' => 'test',
                'eventmanager'  => 'orm_default',
            ]
        ],
        'configuration' => [
            'test' => [
                'metadata_cache'    => 'array',
                'query_cache'       => 'array',
                'result_cache'      => 'array',
                'hydration_cache'   => 'array',
                'driver'            => 'test',
                'generate_proxies'  => true,

                'proxy_dir'         => TestPaths::getPathToDoctrineProxyDir(),
                'proxy_namespace'   => 'DoctrineORMModule\Proxy',
                'filters'           => [],
                'datetime_functions' => [],
                'string_functions' => [],
                'numeric_functions' => [],
                'second_level_cache' => []
            ]
        ],
        'driver' => [
            'test' => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    Module::MODULE_NAME . '\\Entity' => Module::MODULE_NAME,
                ]
            ],
            'orm_default' => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    Module::MODULE_NAME . '\\Entity' => Module::MODULE_NAME,
                ]
            ]
        ]
    ],
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
            ],
            'testDuplicateFixtures' => [
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
                    'name' => ClassLoader::class,
                    'options' => [
                        'classList' => [
                            TestModule1\FooFixture::class,
                            TestModule1\BarFixture::class,
                        ]
                    ]
                ],
            ],
            'testInjectObjectManagerNameFixture' => [
                [
                    'name' => ClassLoader::class,
                    'options' => [
                        'classList' => [
                            TestModule1\TestInjectObjectManagerNameFixture::class
                        ]
                    ]
                ],
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
            ],
            'testDuplicateFixtures' => [
                'fixturesLoader' => 'testDuplicateFixtures',
                'filter' => 'testFilterUsedFixture'
            ],
            'testInjectObjectManagerNameFixture' => [
                'fixturesLoader' => 'testInjectObjectManagerNameFixture',
            ]

        ]

    ]
];