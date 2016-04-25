<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Listener;

use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\Event\ExecutorDispatcherEvent;
use Nnx\DoctrineFixtureModule\Event\FixtureEvent;
use Nnx\DoctrineFixtureModule\Event\FixtureExecutorEvent;
use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;
use Nnx\DoctrineFixtureModule\Listener\AbstractExecutorListener;
use Nnx\DoctrineFixtureModule\Listener\PostExecuteEventSubscriberInterface;
use Nnx\DoctrineFixtureModule\Listener\PreExecuteEventSubscriberInterface;
use Nnx\DoctrineFixtureModule\Listener\RunFixtureEventSubscriberInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\EventManager\EventManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use PHPUnit_Framework_MockObject_MockObject;


/**
 * Class AbstractExecutorListenerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Listener
 */
class AbstractExecutorListenerTest extends AbstractHttpControllerTestCase
{

    /**
     * @var AbstractExecutorListener|PHPUnit_Framework_MockObject_MockObject
     */
    protected $abstractExecutorListener;

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     *
     * @return void
     * @throws \PHPUnit_Framework_Exception
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );
        parent::setUp();
        $this->getApplication();

        $this->abstractExecutorListener = $this->getMockForAbstractClass(AbstractExecutorListener::class);
    }

    /**
     * Проверка ситуации когда в обработчик события возникающего при старте Executor'a пришло событие без заданного Executor'a
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage Executor not specified
     *
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testInvalidExecutorInStartExecutorHandler()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(PreExecuteEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::once())->method('getExecutor')->will(static::returnValue(null));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);
    }


    /**
     * Проверка ситуации когда запустился Executor, во время когда не закончил работу предыдущий
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage You can not start the executor . Already running
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testRunTwoExecutor()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(PreExecuteEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var ExecutorInterface $mockExecutor */
        $mockExecutor = $this->getMock(ExecutorInterface::class);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);
        $eventManager->trigger($eventMock);
    }


    /**
     * Проверка ситуации когда запустился Executor, при этом присутствует фикстура
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage There should not be running fixtures
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testRunExecutorWithPresentFixture()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(PreExecuteEventSubscriberInterface::class);
        $eventManager->addIdentifiers(RunFixtureEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var ExecutorInterface $mockExecutor */
        $mockExecutor = $this->getMock(ExecutorInterface::class);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $executorEventMock */
        $executorEventMock = $this->getMock(FixtureExecutorEvent::class);
        $executorEventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $executorEventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($executorEventMock);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $executorEventMock */
        $fixtureEventMock = $this->getMock(FixtureEvent::class);
        $fixtureEventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $fixtureEventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureEvent::EXECUTE_FIXTURE_EVENT));
        /** @var Fixture $fixtureMock */
        $fixtureMock = $this->getMock(Fixture::class);
        $fixtureEventMock->expects(static::any())->method('getFixture')->will(static::returnValue($fixtureMock));

        $eventManager->trigger($fixtureEventMock);

        $eventManager->trigger($executorEventMock);
    }


    /**
     * Проверка ситуации когда Executor заканчивает работу, при в событие отсутствует Executor
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage Executor not specified
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testInvalidExecutorInEndExecutorHandler()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(PostExecuteEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::once())->method('getExecutor')->will(static::returnValue(null));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::END_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);
    }



    /**
     * Проверка ситуации когда Executor заканчивает работу, при в событие передан другой Executor
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage You can not simultaneously run two Executor
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testOutContextExecutorInEndExecutorHandler()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(PreExecuteEventSubscriberInterface::class);
        $eventManager->addIdentifiers(PostExecuteEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var ExecutorInterface $mockExecutor */
        $mockExecutor = $this->getMock(ExecutorInterface::class);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);

        /** @var ExecutorInterface $mockInvalidExecutor */
        $mockInvalidExecutor = $this->getMock(ExecutorInterface::class);
        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockInvalidExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::END_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);
    }




    /**
     * Проверка ситуации когда запускается фикстура, при этом в событие не передан Executor
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage Executor not specified
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testInvalidExecutorInExecuteFixtureHandler()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(RunFixtureEventSubscriberInterface::class);
        $eventManager->addIdentifiers(PreExecuteEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var ExecutorInterface $mockExecutor */
        $mockExecutor = $this->getMock(ExecutorInterface::class);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);


        /** @var FixtureEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue(null));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureEvent::EXECUTE_FIXTURE_EVENT));

        $eventManager->trigger($eventMock);
    }


    /**
     * Проверка ситуации когда запускается фикстура, при этом отсутствует Executor, в контексте которого произошел запус
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage It failed to get the Executor, to the current context
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testFailedGetExecutorCurrentContextInExecuteFixtureHandler()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(RunFixtureEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var FixtureEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureEvent::class);
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureEvent::EXECUTE_FIXTURE_EVENT));

        $eventManager->trigger($eventMock);
    }


    /**
     * Проверка ситуации когда запускается фикстура, при в событие передан другой Executor
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage Attempting to perform a fixture of incorrect executor
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testOutContextExecutorInExecuteFixtureHandler()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(PreExecuteEventSubscriberInterface::class);
        $eventManager->addIdentifiers(RunFixtureEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var ExecutorInterface $mockExecutor */
        $mockExecutor = $this->getMock(ExecutorInterface::class);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);

        /** @var ExecutorInterface $mockInvalidExecutor */
        $mockInvalidExecutor = $this->getMock(ExecutorInterface::class);
        /** @var FixtureEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockInvalidExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureEvent::EXECUTE_FIXTURE_EVENT));

        $eventManager->trigger($eventMock);
    }


    /**
     * Проверка ситуации когда в обработчик события запуска фикстуры, приходит объект события, у которого не задана фикстура
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @expectedExceptionMessage Fixture not specified
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testInvalidFixtureInExecuteFixtureHandler()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(PreExecuteEventSubscriberInterface::class);
        $eventManager->addIdentifiers(RunFixtureEventSubscriberInterface::class);

        $this->abstractExecutorListener->attach($eventManager);

        /** @var ExecutorInterface $mockExecutor */
        $mockExecutor = $this->getMock(ExecutorInterface::class);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);


        /** @var FixtureEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureEvent::EXECUTE_FIXTURE_EVENT));

        $eventManager->trigger($eventMock);
    }

    /**
     * Проверка корректности отработки событий
     *
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testLifeCycle()
    {
        $eventManager = new EventManager();
        $eventManager->addIdentifiers(PreExecuteEventSubscriberInterface::class);
        $eventManager->addIdentifiers(PostExecuteEventSubscriberInterface::class);
        $eventManager->addIdentifiers(RunFixtureEventSubscriberInterface::class);

        /** @var AbstractExecutorListener|PHPUnit_Framework_MockObject_MockObject $abstractExecutorListener */
        $abstractExecutorListener = $this->getMockForAbstractClass(
            AbstractExecutorListener::class,
            [],
            '',
            true,
            true,
            true,
            [
                'onRunExecutorHandler',
                'onRunFixtureHandler',
                'onFinishFixtureHandler',
                'onFinishExecutorHandler'
            ]
        );

        $abstractExecutorListener->expects(static::once())->method('onRunExecutorHandler')->with(static::isInstanceOf(ExecutorDispatcherEvent::class));
        $abstractExecutorListener->expects(static::once())->method('onRunFixtureHandler')->with(static::isInstanceOf(ExecutorDispatcherEvent::class));
        $abstractExecutorListener->expects(static::once())->method('onFinishFixtureHandler')->with(static::isInstanceOf(ExecutorDispatcherEvent::class));
        $abstractExecutorListener->expects(static::once())->method('onFinishExecutorHandler')->with(static::isInstanceOf(ExecutorDispatcherEvent::class));
        $abstractExecutorListener->attach($eventManager);




        //Запуск Executor'a
        /** @var ExecutorInterface $mockExecutor */
        $mockExecutor = $this->getMock(ExecutorInterface::class);

        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);

        //Запуск фикстуры
        $mockFixture = $this->getMock(Fixture::class);

        /** @var FixtureEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureEvent::EXECUTE_FIXTURE_EVENT));
        $eventMock->expects(static::any())->method('getFixture')->will(static::returnValue($mockFixture));

        $eventManager->trigger($eventMock);

        //Окончакние работы Executor'a
        /** @var FixtureExecutorEvent|PHPUnit_Framework_MockObject_MockObject $eventMock */
        $eventMock = $this->getMock(FixtureExecutorEvent::class);
        $eventMock->expects(static::any())->method('getExecutor')->will(static::returnValue($mockExecutor));
        $eventMock->expects(static::any())->method('getName')->will(static::returnValue(FixtureExecutorEvent::END_EXECUTE_FIXTURES_EVENT));

        $eventManager->trigger($eventMock);
    }
}
