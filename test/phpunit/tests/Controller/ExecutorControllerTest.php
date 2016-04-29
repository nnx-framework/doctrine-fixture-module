<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Nnx\DoctrineFixtureModule\Controller\ExecutorController;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Console\Request;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp;
use Zend\Console\Request as ConsoleRequest;

/**
 * Class ExecutorControllerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Controller
 */
class ExecutorControllerTest extends AbstractConsoleControllerTestCase
{

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getApplication()->getServiceManager()->get('doctrine.entitymanager.test');

        $tool = new SchemaTool($em);
        $tool->dropDatabase();

        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $tool->createSchema($metadata);

        parent::setUp();
    }


    /**
     * Проверка запуска фикстур через Executor
     *
     * @throws \Exception
     */
    public function testRunImportExecutorAction()
    {
        $this->dispatch('nnx:fixture import executor testFilterUsedFixture --object-manager=doctrine.entitymanager.test');
        $this->assertConsoleOutputContains('All fixture completed');
    }


    /**
     * Проверка запуска фикстур через Executor
     *
     * @throws \Exception
     */
    public function testRunPurgeExecutorAction()
    {
        $this->dispatch('nnx:fixture purge executor testFilterUsedFixture --object-manager=doctrine.entitymanager.test');
        $this->assertConsoleOutputContains('All fixture completed');
    }


    /**
     * Проверка запуска фикстур через Executor
     *
     * @throws \Exception
     */
    public function testExecuteFixture()
    {
        $fixtures = [
            FixtureTestApp\TestModule1\FooFixture::class,
            FixtureTestApp\TestModule1\BarFixture::class,
            FixtureTestApp\FixturesDir\FooFixture::class
        ];
        $fixturesStr = implode(' ', $fixtures);

        $commandParts = [
            'nnx:fixture',
            'import',
            'fixture',
            "{$fixturesStr}",
            '--object-manager=doctrine.entitymanager.test'
        ];

        /** @var Request $request */
        $request = $this->getApplication()->getRequest();
        $request->setContent(implode(' ', $commandParts));
        $request->params()->fromArray($commandParts);
        $this->getApplication()->run();

        $this->assertConsoleOutputContains('All fixture completed');
    }

    /**
     * Проверка ситуации когда происходит работа с не консольным запросом
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @expectedExceptionMessage Request is not console
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @throws \Zend\Console\Exception\RuntimeException
     */
    public function testNotConsoleRequest()
    {
        /** @var ServiceLocatorInterface $controllerPluginManager */
        $controllerPluginManager = $this->getApplicationServiceLocator()->get('ControllerLoader');

        /** @var ExecutorController $controller */
        $controller = $controllerPluginManager->get(ExecutorController::class);


        $controller->getExecutorMethod();
    }


    /**
     * Проверка ситуации когда не указан метод Executor'a
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @expectedExceptionMessage Executor method not defined
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @throws \Zend\Console\Exception\RuntimeException
     */
    public function testExecutorMethodNotSpecified()
    {
        /** @var ServiceLocatorInterface $controllerPluginManager */
        $controllerPluginManager = $this->getApplicationServiceLocator()->get('ControllerLoader');

        $request = new ConsoleRequest();

        /** @var ExecutorController $controller */
        $controller = $controllerPluginManager->get(ExecutorController::class);
        $controller->getEventManager()->attach(
            MvcEvent::EVENT_DISPATCH,
            function (MvcEvent $e) {
                $e->stopPropagation(true);
            },
            PHP_INT_MAX
        );
        $controller->dispatch($request);

        $controller->getExecutorMethod();
    }

    /**
     * Проверка ситуации когда указан некорректный методя у Executor'a
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @expectedExceptionMessage Invalid executor method notAllowedMethod
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @throws \Zend\Console\Exception\RuntimeException
     */
    public function testInvalidExecutorMethodNotSpecified()
    {
        /** @var ServiceLocatorInterface $controllerPluginManager */
        $controllerPluginManager = $this->getApplicationServiceLocator()->get('ControllerLoader');

        $request = new ConsoleRequest();
        $request->params()->set('method', 'notAllowedMethod');

        /** @var ExecutorController $controller */
        $controller = $controllerPluginManager->get(ExecutorController::class);
        $controller->getEventManager()->attach(
            MvcEvent::EVENT_DISPATCH,
            function (MvcEvent $e) {
                $e->stopPropagation(true);
            },
            PHP_INT_MAX
        );
        $controller->dispatch($request);

        $controller->getExecutorMethod();
    }


    /**
     * Проверка ситуации когда указан некорректный методя у Executor'a
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @expectedExceptionMessage Executor name is not defined
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @throws \Zend\Console\Exception\RuntimeException
     */
    public function testExecutorNameNotSpecified()
    {
        /** @var ServiceLocatorInterface $controllerPluginManager */
        $controllerPluginManager = $this->getApplicationServiceLocator()->get('ControllerLoader');

        $request = new ConsoleRequest();

        /** @var ExecutorController $controller */
        $controller = $controllerPluginManager->get(ExecutorController::class);
        $controller->getEventManager()->attach(
            MvcEvent::EVENT_DISPATCH,
            function (MvcEvent $e) {
                $e->stopPropagation(true);
            },
            PHP_INT_MAX
        );
        $controller->dispatch($request);

        $controller->getExecutorName();
    }
}
