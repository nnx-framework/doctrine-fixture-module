<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Executor;

use Nnx\DoctrineFixtureModule\Executor\ExecutorAwareTrait;
use PHPUnit_Framework_TestCase;
use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;

/**
 * Class ExecutorAwareTraitTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Executor
 */
class ExecutorAwareTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Трейт для получения Executor'a
     *
     * @var ExecutorAwareTrait
     */
    protected $executorAwareTrait;

    /**
     * Установка окружения
     *
     * @return void
     * @throws \PHPUnit_Framework_Exception
     */
    public function setUp()
    {
        $this->executorAwareTrait = $this->getMockForTrait(ExecutorAwareTrait::class);
    }

    /**
     * Проверка установки/получения executor'a
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function testGetterSetterExecutor()
    {
        /** @var ExecutorInterface $executor */
        $executor = $this->getMock(ExecutorInterface::class);
        static::assertEquals($this->executorAwareTrait, $this->executorAwareTrait->setExecutor($executor));
        static::assertEquals($executor,  $this->executorAwareTrait->getExecutor());
    }

    /**
     * Проверку поведения трейта, когда происходит попытка получить не установленный Executor
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     * @expectedExceptionMessage Fixture executor not found
     *
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function testGetExecutorWherePropertyNotSpecified()
    {
        $this->executorAwareTrait->getExecutor();
    }
}
