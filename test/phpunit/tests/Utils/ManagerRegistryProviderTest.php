<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Utils;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProvider;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ManagerRegistryProviderTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Utils
 */
class ManagerRegistryProviderTest extends AbstractHttpControllerTestCase
{
    /**
     * Провайдер для получеия ManagerRegistry
     *
     * @var ManagerRegistryProvider
     */
    protected $managerRegistryProvider;

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );

        parent::setUp();

        $this->managerRegistryProvider = $this->getApplicationServiceLocator()->get(ManagerRegistryProviderInterface::class);
    }

    /**
     * Проверка получения ManagerRegistry
     *
     * @throws \Nnx\DoctrineFixtureModule\Utils\Exception\RuntimeException
     */
    public function testGetManagerRegistry()
    {
        static::assertInstanceOf(ManagerRegistry::class, $this->managerRegistryProvider->getManagerRegistry());
    }


    /**
     * Проверка ситуации когда не удалось получить  ManagerRegistry
     *
     * @throws \Nnx\DoctrineFixtureModule\Utils\Exception\RuntimeException
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Utils\Exception\RuntimeException
     * @expectedExceptionMessage ManagerRegistry not found
     */
    public function testInvalidManagerRegistry()
    {
        $this->getApplication()->getEventManager()->getSharedManager()->clearListeners('DoctrineManagerRegistry');
        static::assertInstanceOf(ManagerRegistry::class, $this->managerRegistryProvider->getManagerRegistry());
    }

    /**
     * Проврека установки/получения ManagerRegistry
     *
     * @throws \Nnx\DoctrineFixtureModule\Utils\Exception\RuntimeException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testGetterSetterManagerRegistry()
    {
        /** @var ManagerRegistry $managerRegistry */
        $managerRegistry = $this->getMock(ManagerRegistry::class);
        static::assertEquals($this->managerRegistryProvider, $this->managerRegistryProvider->setManagerRegistry($managerRegistry));
        static::assertEquals($managerRegistry, $this->managerRegistryProvider->getManagerRegistry());
    }
}
