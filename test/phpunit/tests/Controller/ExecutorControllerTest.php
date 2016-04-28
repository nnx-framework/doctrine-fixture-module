<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Console\Request;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp;

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
    public function testRunExecutorAction()
    {
        $this->dispatch('nnx:fixture import executor testFilterUsedFixture --object-manager=doctrine.entitymanager.test');
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
}
