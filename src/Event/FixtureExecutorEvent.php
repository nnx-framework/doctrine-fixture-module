<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Event;

use Nnx\DoctrineFixtureModule\Executor\ExecutorAwareTrait;
use Zend\EventManager\Event;

/**
 * Class FixtureExecutorEvent
 *
 * @package Nnx\DoctrineFixtureModule\Event
 */
class FixtureExecutorEvent extends Event
{
    use ExecutorAwareTrait;

    /**
     * Имя события бросаемоего когда происходит запуск работы с фикстурами
     *
     * @var string
     */
    const START_EXECUTE_FIXTURES_EVENT = 'startExecuteFixtures.fixtureExecutor';

    /**
     * Имя события бросаемоего когда происходит окончание работы с фикстурами
     *
     * @var string
     */
    const END_EXECUTE_FIXTURES_EVENT = 'endExecuteFixtures.fixtureExecutor';

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
     * Возвращает имя действия которое выполняет фикстура
     *
     * @return string
     */
    public function getMethod()
    {
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
}
