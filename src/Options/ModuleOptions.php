<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Options;

use Zend\Stdlib\AbstractOptions;
use Nnx\ModuleOptions\ModuleOptionsInterface;


/**
 * Class ModuleOptions
 *
 * @package Nnx\DoctrineFixtureModule\Options
 */
class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{
    /**
     * Конфиг фикстур
     *
     * @var array
     */
    protected $fixturesLoaders;

    /**
     * Конфиг для фильтров фикстур
     *
     * @var array
     */
    protected $filters;

    /**
     * Конфиг для компонентов отвечающих за выполнение фикстур
     *
     * @var array
     */
    protected $executors;

    /**
     * Набор подписчиков на события бросаемые компонентами модуля doctrine/data-fixtures
     *
     * @var array
     */
    protected $defaultFixtureEventListeners = [];

    /**
     * Возвращает конфиг фикстур
     *
     * @return array
     */
    public function getFixturesLoaders()
    {
        return $this->fixturesLoaders;
    }

    /**
     * Устанавливает конфиг фикстур
     *
     * @param array $fixturesLoaders
     *
     * @return $this
     */
    public function setFixturesLoaders(array $fixturesLoaders = [])
    {
        $this->fixturesLoaders = $fixturesLoaders;

        return $this;
    }

    /**
     * Возвращает конфиг для фильтров фикстур
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Устанавливает конфиг для фильтров фикстур
     *
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters = [])
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Возвращает конфиг  для компонентов отвечающих за выполнение фикстур
     *
     * @return array
     */
    public function getExecutors()
    {
        return $this->executors;
    }

    /**
     * Устанавливает конфиг  для компонентов отвечающих за выполнение фикстур
     *
     * @param array $executors
     *
     * @return $this
     */
    public function setExecutors($executors)
    {
        $this->executors = $executors;

        return $this;
    }

    /**
     * Возвращает набор подписчиков на события бросаемые компонентами модуля doctrine/data-fixtures
     *
     * @return array
     */
    public function getDefaultFixtureEventListeners()
    {
        return $this->defaultFixtureEventListeners;
    }

    /**
     * Устанавливает набор подписчиков на события бросаемые компонентами модуля doctrine/data-fixtures
     *
     * @param array $defaultFixtureEventListeners
     *
     * @return $this
     */
    public function setDefaultFixtureEventListeners(array $defaultFixtureEventListeners)
    {
        $this->defaultFixtureEventListeners = $defaultFixtureEventListeners;

        return $this;
    }
}
