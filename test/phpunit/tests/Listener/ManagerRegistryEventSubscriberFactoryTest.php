<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Listener;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Doctrine\Fixture\Persistence\ManagerRegistryEventSubscriber;


/**
 * Class ManagerRegistryEventSubscriberFactoryTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Listener
 */
class ManagerRegistryEventSubscriberFactoryTest extends AbstractHttpControllerTestCase
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
     * Проверка создания ManagerRegistryEventSubscriber
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testCreateManagerRegistryEventSubscriber()
    {
        $managerRegistryEventSubscriber = $this->getApplication()->getServiceManager()->get(ManagerRegistryEventSubscriber::class);
        static::assertInstanceOf(ManagerRegistryEventSubscriber::class, $managerRegistryEventSubscriber);
    }
}
