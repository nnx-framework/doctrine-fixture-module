<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Event;

use Nnx\DoctrineFixtureModule\Event\ExecutorDispatcherEvent;
use PHPUnit_Framework_TestCase;
use Doctrine\Fixture\Fixture;

/**
 * Class ExecutorDispatcherEventTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Event
 */
class ExecutorDispatcherEventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ExecutorDispatcherEvent
     */
    protected $executorDispatcherEvent;


    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->executorDispatcherEvent = new ExecutorDispatcherEvent();
        parent::setUp();
    }

    /**
     * Проверка установки и получения имени метода
     *
     *
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     */
    public function testSetterGetterMethod()
    {
        static::assertEquals($this->executorDispatcherEvent, $this->executorDispatcherEvent->setMethod(ExecutorDispatcherEvent::IMPORT));
        static::assertEquals(ExecutorDispatcherEvent::IMPORT, $this->executorDispatcherEvent->getMethod());
    }

    /**
     * Проверка ситуации когда не установлено значение метода
     *
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     * @expectedExceptionMessage Method not specified
     *
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     */
    public function testNotSpecifiedMethod()
    {
        $this->executorDispatcherEvent->getMethod();
    }

    /**
     * Проверка установки и получения имени метода
     *
     *
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testSetterGetterFixture()
    {
        /** @var  Fixture $fixtureMock */
        $fixtureMock = $this->getMock(Fixture::class);

        static::assertEquals($this->executorDispatcherEvent, $this->executorDispatcherEvent->setFixture($fixtureMock));
        static::assertEquals($fixtureMock, $this->executorDispatcherEvent->getFixture());
    }

    /**
     * Проверка ситуации когда не установлена фикстура
     *
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     * @expectedExceptionMessage Fixture not specified
     *
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     */
    public function testNotSpecifiedFixture()
    {
        $this->executorDispatcherEvent->getFixture();
    }
}
