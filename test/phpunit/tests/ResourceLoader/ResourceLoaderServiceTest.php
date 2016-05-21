<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\ResourceLoader;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderService;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderServiceInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class ResourceLoaderIntegrationTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\ResourceLoader
 */
class ResourceLoaderServiceTest extends AbstractHttpControllerTestCase
{
    /**
     * @var ResourceLoaderService
     */
    protected $resourceLoaderService;

    /**
     * @inheritDoc
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    protected function setUp()
    {
        parent::setUp();

        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );

        $this->resourceLoaderService = $this->getApplicationServiceLocator()->get(ResourceLoaderServiceInterface::class);
    }

    /**
     * Проверка когда в качестве данных о загрузчике фикстур, передан не массив
     *
     * @expectedException \Nnx\DoctrineFixtureModule\ResourceLoader\Exception\InvalidFixtureResourceLoaderConfigException
     * @expectedExceptionMessage Fixture resource loader config is not array
     *
     * @throws \Nnx\DoctrineFixtureModule\ResourceLoader\Exception\InvalidFixtureResourceLoaderConfigException
     */
    public function testFixtureResourceLoaderConfigIsNotArray()
    {
        $this->resourceLoaderService->buildFixtureResourceLoaderConfig(null);
    }


    /**
     * Проверка когда в данных описывающих загрузчик ресурсов, отсутствует информация, о имени плагина загрузчика ресурсов
     *
     * @expectedException \Nnx\DoctrineFixtureModule\ResourceLoader\Exception\InvalidFixtureResourceLoaderConfigException
     * @expectedExceptionMessage Resource loader name not defined
     *
     * @throws \Nnx\DoctrineFixtureModule\ResourceLoader\Exception\InvalidFixtureResourceLoaderConfigException
     */
    public function testResourceLoaderNameNotDefined()
    {
        $this->resourceLoaderService->buildFixtureResourceLoaderConfig([]);
    }


    /**
     * Проверка подготовки конфига, для создания загрузчика ресурсов
     *
     * @throws \Nnx\DoctrineFixtureModule\ResourceLoader\Exception\InvalidFixtureResourceLoaderConfigException
     */
    public function testBuildFixtureResourceLoaderConfig()
    {
        $expected = [
            'name' => 'test',
            'options' => [
                'key1' => 'value1'
            ]
        ];


        $actual = $this->resourceLoaderService->buildFixtureResourceLoaderConfig($expected);

        static::assertEquals($expected, $actual);
    }
}
