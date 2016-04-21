<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Listener;

use Doctrine\Fixture\Persistence\ConnectionRegistryEventSubscriber;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class ConnectionRegistryEventSubscriberFactoryTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Listener
 */
class ConnectionRegistryEventSubscriberFactoryTest extends AbstractHttpControllerTestCase
{

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
    }

    /**
     * Проверка создания ConnectionRegistryEventSubscriber
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testCreateConnectionRegistryEventSubscriber()
    {
        $connectionRegistryEventSubscriber = $this->getApplication()->getServiceManager()->get(ConnectionRegistryEventSubscriber::class);
        static::assertInstanceOf(ConnectionRegistryEventSubscriber::class, $connectionRegistryEventSubscriber);
    }
}
