<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\ResourceLoader;

use Doctrine\Fixture\Fixture;

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
        $this->setResourceLoaderManager($resourceLoaderManager);
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

    /**
     * @inheritDoc
     * @throws \Nnx\DoctrineFixtureModule\ResourceLoader\Exception\InvalidFixtureResourceLoaderConfigException
     */
    public function loadResourceForFixture(Fixture $fixture)
    {
        $classFixture = get_class($fixture);

        $classFixtureToResourceLoader = $this->getClassFixtureToResourceLoader();
        if (array_key_exists($classFixture, $classFixtureToResourceLoader)) {
            $fixtureResourceLoaderConfig = $this->buildFixtureResourceLoaderConfig($classFixtureToResourceLoader[$classFixture]);
            $fixtureResourceLoader = $this->getResourceLoaderManager()->get($fixtureResourceLoaderConfig['name'], $fixtureResourceLoaderConfig['options']);
            $fixtureResourceLoader->loadResourceForFixture($fixture);
        }
    }

    /**
     * Подготавливает данные необходимые для получения загрузчика ресурсов фикстуры
     *
     * @param mixed $fixtureResourceLoaderConfigData
     *
     * @throws \Nnx\DoctrineFixtureModule\ResourceLoader\Exception\InvalidFixtureResourceLoaderConfigException
     *
     * @return array
     */
    public function buildFixtureResourceLoaderConfig($fixtureResourceLoaderConfigData = null)
    {
        $fixtureResourceLoaderConfig = [];

        if (!is_array($fixtureResourceLoaderConfigData)) {
            $errMsg = 'Fixture resource loader config is not array';
            throw new Exception\InvalidFixtureResourceLoaderConfigException($errMsg);
        }

        if (!array_key_exists('name', $fixtureResourceLoaderConfigData)) {
            $errMsg = 'Resource loader name not defined';
            throw new Exception\InvalidFixtureResourceLoaderConfigException($errMsg);
        }
        $fixtureResourceLoaderConfig['name'] = (string)$fixtureResourceLoaderConfigData['name'];

        $fixtureResourceLoaderConfig['options'] = [];
        if (array_key_exists('options', $fixtureResourceLoaderConfigData)) {
            $fixtureResourceLoaderConfig['options'] = (array)$fixtureResourceLoaderConfigData['options'];
        }

        return $fixtureResourceLoaderConfig;
    }
}
