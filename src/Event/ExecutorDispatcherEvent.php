<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Event;

use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\Executor\ExecutorAwareTrait;
use Zend\EventManager\Event;

/**
 * Class ExecutorDispatcherEvent
 *
 * @package Nnx\DoctrineFixtureModule\Event
 */
class ExecutorDispatcherEvent extends Event
{
    use ExecutorAwareTrait;

    /**
     * Событие бросаемое при старте Executor'a
     *
     * @var string
     */
    const RUN_EXECUTOR_EVENT = 'runExecutorEvent.executorDispatcher';

    /**
     * Событие бросаемое при старте фикстуры
     *
     * @var string
     */
    const RUN_FIXTURE_EVENT = 'runFixtureEvent.executorDispatcher';

    /**
     * Событие бросаемое после окончания работы фикстуры
     *
     * @var string
     */
    const FINISH_FIXTURE_EVENT = 'finishFixtureEvent.executorDispatcher';

    /**
     * Событие бросаемое после окончания работы Executor'a
     *
     * @var string
     */
    const FINISH_EXECUTOR_EVENT = 'finishExecutorEvent.executorDispatcher';

    /**
     * Имя действия когда происходит загрузка данных из фикстуры
     *
     * @var string
     */
    const IMPORT = 'import';

    /**
     * Имя действия когда происходит откат данных
     *
     * @var string
     */
    const PURGE = 'purge';

    /**
     * Действие которое выполняет фикстура
     *
     * @var string
     */
    protected $method;

    /**
     * Обрабатываемя фикстура
     *
     * @var Fixture
     */
    protected $fixture;

    /**
     * Возвращает имя действия которое выполняет фикстура
     *
     * @return string
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     */
    public function getMethod()
    {
        if (null === $this->method) {
            $errMsg = 'Method not specified';
            throw new Exception\RuntimeException($errMsg);
        }
        return $this->method;
    }

    /**
     * Устанавливает имя действия которое выполняет фикстура
     *
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Возвращает обрабатываемую фикстуру
     *
     * @return Fixture
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     */
    public function getFixture()
    {
        if (null === $this->fixture) {
            $errMsg = 'Fixture not specified';
            throw new Exception\RuntimeException($errMsg);
        }
        return $this->fixture;
    }

    /**
     * Устанавливает обрабатываемую фикстуру
     *
     * @param Fixture $fixture
     *
     * @return $this
     */
    public function setFixture(Fixture $fixture)
    {
        $this->fixture = $fixture;

        return $this;
    }
}
