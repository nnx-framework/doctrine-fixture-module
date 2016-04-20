<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManager;
use Doctrine\Fixture\Filter\ChainFilter;
use Nnx\DoctrineFixtureModule\Filter\ChainFilterFactory;
use Doctrine\Fixture\Filter\GroupedFilter;
use Nnx\DoctrineFixtureModule\Filter\GroupedFilterFactory;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterAbstractFactory;
use Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture;
use Nnx\DoctrineFixtureModule\Filter\FilterUsedFixtureFactory;

return [
    FixtureFilterManager::CONFIG_KEY => [
        'invokables'         => [

        ],
        'factories'          => [
            ChainFilter::class       => ChainFilterFactory::class,
            GroupedFilter::class     => GroupedFilterFactory::class,
            FilterUsedFixture::class => FilterUsedFixtureFactory::class
        ],
        'abstract_factories' => [
            FixtureFilterAbstractFactory::class => FixtureFilterAbstractFactory::class
        ],
        'shared'             => [

        ]
    ],
];


