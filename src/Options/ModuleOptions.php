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
    protected $fixtureInitializer = [];

    /**
     * Инициалайзеры, создаваемые заново перед каждым запуском фикстур. При создание этих инициайзеров, им передаются
     * данные контекста
     *
     * @var array
     */
    protected $contextInitializer = [];

    /**
     * Секция в которой описывается какой загрузчик ресурсво нужно применять, для фикстуры
     *
     * @var array
     */
    protected $resourceLoader = [];

    /**
     * Имя сервиса, по которому можно получить локатор, для создания сущностей. Используется в SimpleFixture
     *
     * @var string
     */
    protected $simpleFixtureEntityLocator;

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
    public function getFixtureInitializer()
    {
        return $this->fixtureInitializer;
    }

    /**
     * Устанавливает набор подписчиков на события бросаемые компонентами модуля doctrine/data-fixtures
     *
     * @param array $fixtureInitializer
     *
     * @return $this
     */
    public function setFixtureInitializer(array $fixtureInitializer)
    {
        $this->fixtureInitializer = $fixtureInitializer;

        return $this;
    }

    /**
     * Возвращает инициалайзеры, создаваемые заново перед каждым запуском фикстур. При создание этих инициайзеров, им передаются
     * данные контекста
     *
     * @return array
     */
    public function getContextInitializer()
    {
        return $this->contextInitializer;
    }

    /**
     * Устанавлиает инициалайзеры, создаваемые заново перед каждым запуском фикстур. При создание этих инициайзеров, им передаются
     * данные контекста
     *
     * @param array $contextInitializer
     *
     * @return $this
     */
    public function setContextInitializer($contextInitializer)
    {
        $this->contextInitializer = $contextInitializer;

        return $this;
    }

    /**
     * Секция в которой описывается какой загрузчик ресурсво нужно применять, для фикстуры
     *
     * @return array
     */
    public function getResourceLoader()
    {
        return $this->resourceLoader;
    }

    /**
     * Секция в которой описывается какой загрузчик ресурсво нужно применять, для фикстуры
     *
     * @param array $resourceLoader
     *
     * @return $this
     */
    public function setResourceLoader(array $resourceLoader = [])
    {
        $this->resourceLoader = $resourceLoader;

        return $this;
    }

    /**
     * Возвращает имя сервиса, по которому можно получить локатор, для создания сущностей. Используется в SimpleFixture
     *
     * @return string
     */
    public function getSimpleFixtureEntityLocator()
    {
        return $this->simpleFixtureEntityLocator;
    }

    /**
     * Устанавливает имя сервиса, по которому можно получить локатор, для создания сущностей. Используется в SimpleFixture
     *
     * @param string $simpleFixtureEntityLocator
     *
     * @return $this
     */
    public function setSimpleFixtureEntityLocator($simpleFixtureEntityLocator)
    {
        $this->simpleFixtureEntityLocator = $simpleFixtureEntityLocator;

        return $this;
    }
}
