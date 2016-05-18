<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\ResourceLoader;

use Interop\Container\ContainerInterface;

/**
 * Interface ResourceLoaderManagerInterface
 *
 * @package Nnx\DoctrineFixtureModule\ResourceLoader
 *
 * @method ResourceLoaderInterface get($id, array $options = null)
 */
interface ResourceLoaderManagerInterface extends ContainerInterface
{
}
