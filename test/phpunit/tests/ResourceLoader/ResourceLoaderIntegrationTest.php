<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\ResourceLoader;

use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Module as DoctrineFixtureModule;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderInterface;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderManager;
use Nnx\ZF2TestToolkit\Utils\OverrideAppConfigTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Doctrine\Fixture\Loader\ClassLoader;

/**
 * Class ResourceLoaderIntegrationTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\ResourceLoader
 */
class ResourceLoaderIntegrationTest extends AbstractHttpControllerTestCase
    implements
        Fixture,
        ResourceLoaderInterface,
        FactoryInterface,
        MutableCreationOptionsInterface
{
    use OverrideAppConfigTrait, MutableCreationOptionsTrait;

    /**
     * Так как данный класс, является тестовым загрузчиком ресурсов, то свойство используется, для проверки корректного,
     * конфигурирования загрузчика ресурсов
     *
     * @var array
     */
    protected $resourceLoaderTestOptions = [];

    /**
     * Тестовые опциии для загрузчика ресурсов
     *
     * @var array
     */
    protected static $testResourceLoaderOptions = [
        'key1' => 'value1'
    ];

    /**
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testResourceLoader()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );

        $this->overrideAppConfig([
            DoctrineFixtureModule::CONFIG_KEY => [
                'fixturesLoaders' => [
                    'testResourceLoader' => [
                        [
                            'name'    => ClassLoader::class,
                            'options' => [
                                'classList' => [
                                    static::class
                                ]
                            ]
                        ]
                    ]
                ],
                'executors' => [
                    'testResourceLoader' => [
                        'fixturesLoader' => 'testResourceLoader'
                    ]
                ],
                'resourceLoader' => [
                    static::class => [
                        'name' => static::class,
                        'options' => static::$testResourceLoaderOptions
                    ]
                ]
            ],
            ResourceLoaderManager::CONFIG_KEY => [
                'factories' => [
                    static::class => static::class
                ]
            ]
        ]);

        /** @var FixtureExecutorManagerInterface $fixtureExecutorManager */
        $fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);
        $executor = $fixtureExecutorManager->get('testResourceLoader');
        $executor->import();
    }

    /**
     * @inheritDoc
     */
    public function import()
    {
    }

    /**
     * @inheritDoc
     */
    public function purge()
    {
    }

    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $resourceLoader = new static();

        $resourceLoader->setResourceLoaderTestOptions($this->getCreationOptions());

        return $resourceLoader;
    }


    /**
     * @return array
     */
    public function getResourceLoaderTestOptions()
    {
        return $this->resourceLoaderTestOptions;
    }

    /**
     * @param array $resourceLoaderTestOptions
     *
     * @return $this
     */
    public function setResourceLoaderTestOptions(array $resourceLoaderTestOptions = [])
    {
        $this->resourceLoaderTestOptions = $resourceLoaderTestOptions;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function loadResourceForFixture(Fixture $fixture)
    {
        static::assertEquals(static::$testResourceLoaderOptions, $this->getResourceLoaderTestOptions());
    }
}
