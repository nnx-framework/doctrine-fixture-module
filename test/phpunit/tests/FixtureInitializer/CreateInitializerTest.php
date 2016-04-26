<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureInitializer;

use Nnx\DoctrineFixtureModule\FixtureInitializer\ConnectionRegistryEventSubscriber;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ManagerRegistryEventSubscriber;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManagerInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class ConnectionRegistryEventSubscriberTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureInitializer
 */
class CreateInitializerTest extends AbstractHttpControllerTestCase
{

    /**
     * Менеджер для работы с инициалайзерами фикстур
     *
     * @var FixtureInitializerManagerInterface
     */
    protected $fixtureInitializerManager;

    /**
     * Данные для проверки создания инициалайзеров
     *
     * @return array
     */
    public function dataCreateInitializer()
    {
        return [
            [
                'serviceName' => ConnectionRegistryEventSubscriber::class,
                'options' => [],
                'className' => ConnectionRegistryEventSubscriber::class,
                'shared' => true
            ],
            [
                'serviceName' => ManagerRegistryEventSubscriber::class,
                'options' => [],
                'className' => ManagerRegistryEventSubscriber::class,
                'shared' => true
            ]
        ];
    }

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     *
     * @return void
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );
        parent::setUp();

        $this->fixtureInitializerManager = $this->getApplicationServiceLocator()->get(FixtureInitializerManagerInterface::class);
    }


    /**
     * Проверка создания инициалайзеров
     *
     * @dataProvider dataCreateInitializer
     *
     * @param       $serviceName
     * @param array $options
     * @param       $className
     *
     * @param       $shared
     *
     * @return void
     */
    public function testCreateInitializer($serviceName, array $options = [], $className, $shared)
    {
        $fixtureInitializer = $this->fixtureInitializerManager->get($serviceName, $options);
        static::assertInstanceOf($className, $fixtureInitializer);

        $flagSharedActual = $fixtureInitializer === $this->fixtureInitializerManager->get($serviceName, $options);

        static::assertEquals($shared, $flagSharedActual);
    }
}
