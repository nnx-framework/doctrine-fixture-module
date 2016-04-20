<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Filter;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;
use Nnx\DoctrineFixtureModule\Filter\Exception\RuntimeException as FactoryRuntimeException;
use Doctrine\Fixture\Filter\ChainFilter;

/**
 * Class FixtureFilterAbstractFactoryTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Loader
 */
class FixtureFilterAbstractFactoryTest extends AbstractHttpControllerTestCase
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
                            'badFilterConfig' => 'notArray'
                        ]
                    ],
                    'Item [nnx_doctrine_fixture_module][filters][badFilterConfig] of type string is invalid. Must array'
                ],
                [
                    'testServiceName',
                    [
                        'testServiceName' => [
                            'badFilterConfig' => []
                        ]
                    ],
                    'Required parameter [nnx_doctrine_fixture_module][filters][badFilterConfig][\'name\'] not found'
                ],
                [
                    'testServiceName',
                    [
                        'testServiceName' => [
                            'badFilterConfig' => [
                                'name' => false
                            ]
                        ]
                    ],
                    'Parameter [nnx_doctrine_fixture_module][filters][badFilterConfig][\'name\'] of type boolean is invalid. Must string'
                ],
                [
                    'testServiceName',
                    [
                        'testServiceName' => [
                            'badFilterConfig' => [
                                'name' => 'test',
                                'options' => false
                            ]
                        ]
                    ],
                    'Parameter [nnx_doctrine_fixture_module][filters][badFilterConfig][\'options\'] of type boolean is invalid. Must array'
                ]

            ];
    }


    /**
     * Проверка ситуаций когда абстрактной фабрике передается некорректный конфиг
     *
     * @dataProvider dataTestInvalidConfig
     *
     * @param $serviceName
     * @param $filtersConfig
     * @param $expectedTextException
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidConfig($serviceName, $filtersConfig, $expectedTextException)
    {
        $appServiceLocator = $this->getApplicationServiceLocator();
        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsManager */
        $moduleOptionsManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsManager->get(ModuleOptions::class);
        $moduleOptions->setFilters($filtersConfig);

        /** @var  FixtureFilterManagerInterface $filterLoaderManager */
        $filterLoaderManager = $appServiceLocator->get(FixtureFilterManagerInterface::class);

        $currentException = null;
        try {
            $filterLoaderManager->get($serviceName);
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
     * Проверка создания цепочки фильтров
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testCreateLoader()
    {
        $appServiceLocator = $this->getApplicationServiceLocator();
        /** @var  FixtureFilterManagerInterface $fixtureFilterManager */
        $fixtureFilterManager = $appServiceLocator->get(FixtureFilterManagerInterface::class);

        $testChain = $fixtureFilterManager->get('testChainFixtureFilter');

        static::assertInstanceOf(ChainFilter::class, $testChain);
    }
}
