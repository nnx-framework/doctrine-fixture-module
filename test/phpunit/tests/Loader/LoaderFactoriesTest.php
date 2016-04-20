<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Loader;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Doctrine\Fixture\Loader\ChainLoader;
use Doctrine\Fixture\Loader\ClassLoader;
use Doctrine\Fixture\Loader\DirectoryLoader;
use Doctrine\Fixture\Loader\GlobLoader;
use Doctrine\Fixture\Loader\RecursiveDirectoryLoader;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;


/**
 * Class LoaderFactoriesTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Loader
 */
class LoaderFactoriesTest extends AbstractHttpControllerTestCase
{
    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToDefaultAppConfig()
        );

        parent::setUp();
    }

    /**
     * Данные для тестирования фабрик
     *
     * @return array
     */
    public function dataLoaderFactories()
    {
        return [
            [
                ChainLoader::class,
                [
                    'loaderList' => []
                ],
                ChainLoader::class
            ],
            [
                ClassLoader::class,
                [
                    'classList' => []
                ],
                ClassLoader::class
            ],
            [
                DirectoryLoader::class,
                [
                    'directory' => __DIR__
                ],
                DirectoryLoader::class
            ],
            [
                GlobLoader::class,
                [
                    'directory' => __DIR__
                ],
                GlobLoader::class
            ],
            [
                RecursiveDirectoryLoader::class,
                [
                    'directory' => __DIR__
                ],
                RecursiveDirectoryLoader::class
            ]
        ];
    }

    /**
     * Тестирование фабрик
     *
     * @dataProvider dataLoaderFactories
     *
     * @param $serviceName
     * @param $options
     * @param $expectedClass
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testLoaderFactories($serviceName, $options, $expectedClass)
    {
        /** @var FixtureLoaderManagerInterface $loaderManager */
        $loaderManager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);

        static::assertInstanceOf($expectedClass, $loaderManager->get($serviceName, $options));
    }
}
