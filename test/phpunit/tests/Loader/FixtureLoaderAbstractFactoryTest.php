<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Loader;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;
use Nnx\DoctrineFixtureModule\Loader\Exception\RuntimeException as FactoryRuntimeException;
use Doctrine\Fixture\Loader\ChainLoader;

/**
 * Class FixtureLoaderAbstractFactoryTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Loader
 */
class FixtureLoaderAbstractFactoryTest extends AbstractHttpControllerTestCase
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
            include TestPaths::getPathToFixtureTestAppConfig()
        );

        parent::setUp();
    }


    /**
     * Данные для тестирования ситуаций когда фабрике передали некорректный конфиг
     *
     * @return array
     */
    public function dataTestInvalidConfig()
    {
        return
            [
                [
                    'testServiceName',
                    [
                        'testServiceName' => [
                            'badLoaderConfig' => 'notArray'
                        ]
                    ],
                    'Item [nnx_doctrine_fixture_module][fixtures][badLoaderConfig] of type string is invalid. Must array'
                ],
                [
                    'testServiceName',
                    [
                        'testServiceName' => [
                            'badLoaderConfig' => []
                        ]
                    ],
                    'Required parameter [nnx_doctrine_fixture_module][fixtures][badLoaderConfig][\'name\'] not found'
                ],
                [
                    'testServiceName',
                    [
                        'testServiceName' => [
                            'badLoaderConfig' => [
                                'name' => false
                            ]
                        ]
                    ],
                    'Parameter [nnx_doctrine_fixture_module][fixtures][badLoaderConfig][\'name\'] of type boolean is invalid. Must string'
                ],
                [
                    'testServiceName',
                    [
                        'testServiceName' => [
                            'badLoaderConfig' => [
                                'name' => 'test',
                                'options' => false
                            ]
                        ]
                    ],
                    'Parameter [nnx_doctrine_fixture_module][fixtures][badLoaderConfig][\'options\'] of type boolean is invalid. Must array'
                ]

            ];
    }


    /**
     * Проверка ситуаций когда абстрактной фабрике передается некорректный конфиг
     *
     * @dataProvider dataTestInvalidConfig
     *
     * @param $serviceName
     * @param $fixturesConfig
     * @param $expectedTextException
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidConfig($serviceName, $fixturesConfig, $expectedTextException)
    {
        $appServiceLocator = $this->getApplicationServiceLocator();
        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);
        $moduleOptions->setFixturesLoaders($fixturesConfig);

        /** @var  FixtureLoaderManagerInterface $fixtureLoaderManager */
        $fixtureLoaderManager = $appServiceLocator->get(FixtureLoaderManagerInterface::class);

        $currentException = null;
        try {
            $fixtureLoaderManager->get($serviceName);
        } catch (\Exception $e) {
            $currentException = $e;
        }

        static::assertInstanceOf(ServiceNotCreatedException::class, $currentException);
        $previousException = $currentException->getPrevious();
        static::assertInstanceOf(ServiceNotCreatedException::class, $previousException);
        $factoryException = $previousException->getPrevious();
        static::assertInstanceOf(FactoryRuntimeException::class, $factoryException);
        static::assertEquals($expectedTextException, $factoryException->getMessage());
    }

    /**
     * Проверка создания цепочки Loader'ов
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testCreateLoader()
    {
        $appServiceLocator = $this->getApplicationServiceLocator();
        /** @var  FixtureLoaderManagerInterface $fixtureLoaderManager */
        $fixtureLoaderManager = $appServiceLocator->get(FixtureLoaderManagerInterface::class);

        $testChain = $fixtureLoaderManager->get('testChainFixtureLoader');

        static::assertInstanceOf(ChainLoader::class, $testChain);
    }
}
