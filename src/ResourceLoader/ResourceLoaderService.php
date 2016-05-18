<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\ResourceLoader;

/**
 * Class ResourceLoaderService
 *
 * @package Nnx\DoctrineFixtureModule\ResourceLoader
 */
class ResourceLoaderService implements ResourceLoaderServiceInterface
{

    /**
     * Ключем является имя класса фикстуры, а значением, конфиг описыайющи загрузчик ресурсов
     *
     * @var array
     */
    protected $classFixtureToResourceLoader = [];

    /**
     * Менеджер плагинов, отвечающиз за загрузку ресурсов для фикстур
     *
     * @var ResourceLoaderManagerInterface
     */
    protected $resourceLoaderManager;

    /**
     * ResourceLoaderService constructor.
     *
     * @param ResourceLoaderManagerInterface $resourceLoaderManager
     */
    public function __construct(ResourceLoaderManagerInterface $resourceLoaderManager)
    {
        $this->resourceLoaderManager = $resourceLoaderManager;
    }

    /**
     * Возвращает массив ключем которого является имя класса фикстуры, а значением, конфиг описыайющи загрузчик ресурсов
     *
     * @return array
     */
    public function getClassFixtureToResourceLoader()
    {
        return $this->classFixtureToResourceLoader;
    }

    /**
     * Возвращает массив ключем которого является имя класса фикстуры, а значением, конфиг описыайющи загрузчик ресурсов
     *
     * @param array $classFixtureToResourceLoader
     *
     * @return $this
     */
    public function setClassFixtureToResourceLoader(array $classFixtureToResourceLoader = [])
    {
        $this->classFixtureToResourceLoader = $classFixtureToResourceLoader;

        return $this;
    }

    /**
     * Возвращает менеджер плагинов, отвечающих за загрузку ресурсов для фикстур
     *
     * @return ResourceLoaderManagerInterface
     */
    public function getResourceLoaderManager()
    {
        return $this->resourceLoaderManager;
    }

    /**
     * Устанавливает менеджер плагинов, отвечающих за загрузку ресурсов для фикстур
     *
     * @param ResourceLoaderManagerInterface $resourceLoaderManager
     *
     * @return $this
     */
    public function setResourceLoaderManager($resourceLoaderManager)
    {
        $this->resourceLoaderManager = $resourceLoaderManager;

        return $this;
    }
}
