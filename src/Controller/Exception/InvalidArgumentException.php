<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Controller\Exception;

use Nnx\DoctrineFixtureModule\Exception\InvalidArgumentException as BaseException;

/**
 * Class InvalidArgumentException
 *
 * @package Nnx\DoctrineFixtureModule\Controller\Exception
 */
class InvalidArgumentException extends BaseException implements
    ExceptionInterface
{
}
