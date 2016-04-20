<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Filter;

use Interop\Container\ContainerInterface;
use Doctrine\Fixture\Filter\Filter;

/**
 * Interface FixtureFilterManagerInterface
 *
 * @package Nnx\DoctrineFixtureModule\Loader
 *
 * @method Filter get($id, array $options = [])
 */
interface FixtureFilterManagerInterface extends ContainerInterface
{
}
