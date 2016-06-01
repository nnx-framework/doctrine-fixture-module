<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture;

use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerAwareTrait;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ObjectManagerNameAwareTrait;

abstract class AbstractSimpleFixture implements SimpleFixtureInterface
{
    use SimpleFixtureTrait, ObjectManagerNameAwareTrait, FixtureDataReaderManagerAwareTrait;
}