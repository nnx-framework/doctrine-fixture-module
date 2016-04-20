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
 * Class FixtureEvent
 *
 * @package Nnx\DoctrineFixtureModule\Event
 */
class FixtureEvent extends Event
{
    use ExecutorAwareTrait;

    /**
     * Имя события бросаемоего когда происходит выполнение фикстуры
     *
     * @var string
     */
    const EXECUTE_FIXTURE_EVENT = 'executeFixture.fixtureExecutor';

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

    /**
     * Возвращает обрабатываемую фикстуру
     *
     * @return Fixture
     */
    public function getFixture()
    {
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
