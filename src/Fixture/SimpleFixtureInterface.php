<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture;

use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerAwareInterface;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ObjectManagerNameAwareInterface;

/**
 * Interface SimpleFixtureInterface
 *
 * @package Nnx\DoctrineFixtureModule\Fixture
 */
interface SimpleFixtureInterface extends Fixture, ObjectManagerNameAwareInterface, FixtureDataReaderManagerAwareInterface
{
}
