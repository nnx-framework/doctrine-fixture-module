<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureInitializer;

use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManager;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManagerInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class FixtureInitializerManagerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureInitializer
 */
class FixtureInitializerManagerTest extends AbstractHttpControllerTestCase
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
     * Проверка получения менеджера плагинов для работы с инициалайзерами фикстур
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testCreateFixtureInitializerManager()
    {
        $fixtureInitializerManager = $this->getApplicationServiceLocator()->get(FixtureInitializerManagerInterface::class);
        static::assertInstanceOf(FixtureInitializerManager::class, $fixtureInitializerManager);
    }


    /**
     * Проверка получения менеджера плагинов для работы с инициалайзерами фикстур
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     * @expectedExceptionMessage Plugin of type stdClass is invalid; must implement Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerInterface
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidPlugin()
    {
        /** @var AbstractPluginManager $fixtureInitializerManager */
        $fixtureInitializerManager = $this->getApplicationServiceLocator()->get(FixtureInitializerManagerInterface::class);


        $fixtureInitializerManager->setInvokableClass(\stdClass::class, \stdClass::class);

        $fixtureInitializerManager->get(\stdClass::class);
    }
}
