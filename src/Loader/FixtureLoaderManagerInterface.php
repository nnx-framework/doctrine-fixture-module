<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Loader;

use Interop\Container\ContainerInterface;
use Doctrine\Fixture\Loader\Loader;

/**
 * Interface FixtureLoaderManagerInterface
 *
 * @package Nnx\DoctrineFixtureModule\Loader
 *          
 * @method Loader get($id, array $options = [])
 */
interface FixtureLoaderManagerInterface extends ContainerInterface
{
}
