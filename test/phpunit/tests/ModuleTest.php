<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Module;

/**
 * Class ModuleTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test
 */
class ModuleTest extends AbstractHttpControllerTestCase
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
     * Проверка что модуль загружается
     *
     * @return void
     */
    public function testLoadModule()
    {
        $this->assertModulesLoaded([Module::MODULE_NAME]);
    }

    /**
     * Проверка ситуации когда в модуль придет некорректный ModuleManager
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Exception\InvalidArgumentException
     * @expectedExceptionMessage Module manager not implement Zend\ModuleManager\ModuleManager
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Nnx\DoctrineFixtureModule\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidModuleManager()
    {
        $module = new Module();
        /** @var ModuleManagerInterface $moduleManagerMock */
        $moduleManagerMock = $this->getMock(ModuleManagerInterface::class);

        $module->init($moduleManagerMock);
    }



    /**
     * Проверка ситуации когда не удается получить ServiceLocator
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Exception\InvalidArgumentException
     * @expectedExceptionMessage Service locator not implement Zend\ServiceManager\ServiceLocatorInterface
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Nnx\DoctrineFixtureModule\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidServiceManager()
    {
        $module = new Module();
        /** @var ModuleManager|\PHPUnit_Framework_MockObject_MockObject $moduleManagerMock */
        $moduleManagerMock = $this->getMock(ModuleManager::class, [], [], '', false);

        /** @var ModuleEvent|\PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(ModuleEvent::class);
        $eventMock->expects(static::once())->method('getParam')->with(static::equalTo('ServiceManager'))->will(static::returnValue(null));

        $moduleManagerMock->expects(static::once())->method('getEvent')->will(static::returnValue($eventMock));

        $module->init($moduleManagerMock);
    }



    /**
     * Проверка ситуации когда не удается получить ServiceListener
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Exception\InvalidArgumentException
     * @expectedExceptionMessage ServiceListener not implement Zend\ModuleManager\Listener\ServiceListenerInterface
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Nnx\DoctrineFixtureModule\Exception\InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidServiceListener()
    {
        $module = new Module();
        /** @var ModuleManager|\PHPUnit_Framework_MockObject_MockObject $moduleManagerMock */
        $moduleManagerMock = $this->getMock(ModuleManager::class, [], [], '', false);

        /** @var ModuleEvent|\PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(ModuleEvent::class);
        $moduleManagerMock->expects(static::once())->method('getEvent')->will(static::returnValue($eventMock));

        /** @var ServiceLocatorInterface||\PHPUnit_Framework_MockObject_MockObject $slMock */
        $slMock = $this->getMock(ServiceLocatorInterface::class);
        $slMock->expects(static::once())->method('get')->with(static::equalTo('ServiceListener'))->will(static::returnValue(null));

        $eventMock->expects(static::once())->method('getParam')->with(static::equalTo('ServiceManager'))->will(static::returnValue($slMock));


        $module->init($moduleManagerMock);
    }
}
