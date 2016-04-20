<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener\Exception;

use Nnx\DoctrineFixtureModule\Exception\DomainException as BaseException;

/**
 * Class DomainException
 *
 * @package Nnx\DoctrineFixtureModule\Listener\Exception
 */
class DomainException extends BaseException implements
    ExceptionInterface
{
}
