<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Filter;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Doctrine\Fixture\Filter\ChainFilter;
use Doctrine\Fixture\Filter\GroupedFilter;

/**
 * Class FilterFactoriesTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Filter
 */
class FilterFactoriesTest extends AbstractHttpControllerTestCase
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
     * Данные для тестирования фабрик
     *
     * @return array
     */
    public function dataLoaderFactories()
    {
        return [
            [
                ChainFilter::class,
                [
                    'filterList' => []
                ],
                ChainFilter::class
            ],
            [
                GroupedFilter::class,
                [
                    'allowedGroupList' => [],
                    'onlyImplementors' => true

                ],
                GroupedFilter::class
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
    public function testFilterFactories($serviceName, $options, $expectedClass)
    {
        /** @var FixtureFilterManagerInterface $fixtureManager */
        $fixtureManager = $this->getApplicationServiceLocator()->get(FixtureFilterManagerInterface::class);

        static::assertInstanceOf($expectedClass, $fixtureManager->get($serviceName, $options));
    }
}
