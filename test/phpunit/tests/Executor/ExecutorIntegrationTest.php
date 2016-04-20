<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Executor;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Executor\Executor;


/**
 * Class ExecutorIntegrationTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Executor
 */
class ExecutorIntegrationTest extends AbstractHttpControllerTestCase
{

    /**
     * Менеджер для работы с компонентами отвечающих за исполнение фикстур
     *
     * @var FixtureExecutorManagerInterface
     */
    protected $fixtureExecutorManager;

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

        $this->fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);
    }

    /**
     * Проверка создания Executor'a
     *
     * @return void
     */
    public function testCreateExecutor()
    {
        $executor = $this->fixtureExecutorManager->get('testExecutor');

        static::assertInstanceOf(Executor::class, $executor);
    }
}
