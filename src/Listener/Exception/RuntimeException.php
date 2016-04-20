<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener\Exception;

use Nnx\DoctrineFixtureModule\Exception\RuntimeException as BaseException;

/**
 * Class RuntimeException
 *
 * @package Nnx\DoctrineFixtureModule\Listener\Exception
 */
class RuntimeException extends BaseException implements
    ExceptionInterface
{
}
