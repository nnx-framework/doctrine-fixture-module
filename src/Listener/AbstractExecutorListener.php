<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener;

use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\Event\ExecutorDispatcherEvent;
use Nnx\DoctrineFixtureModule\Event\FixtureExecutorEvent;
use Nnx\DoctrineFixtureModule\Event\FixtureEvent;
use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerInterface;

/**
 * Class AbstractExecutorListener
 *
 * @package Nnx\DoctrineFixtureModule\Listener
 */
abstract class AbstractExecutorListener extends AbstractListenerAggregate implements ExecutorDispatcherInterface
{
    use EventManagerAwareTrait;

    /**
     * Прототип для события
     *
     * @var ExecutorDispatcherEvent
     */
    protected $executorDispatcherEventPrototype;

    /**
     * Идендфикаторы для менеджера событий
     *
     * @var array
     */
    protected $eventIdentifier = [
        ExecutorDispatcherInterface::class
    ];

    /**
     * Executor который в данный момент выполняется
     *
     * @var ExecutorInterface
     */
    protected $currentExecutor;

    /**
     * Фикстура которая в данный момент выполняется
     *
     * @var Fixture
     */
    protected $currentFixture;

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEventManager = $events->getSharedManager();

        $sharedEventManager->attach(PreExecuteEventSubscriberInterface::class, FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT, [$this, 'startExecutorHandler']);
        $sharedEventManager->attach(PostExecuteEventSubscriberInterface::class, FixtureExecutorEvent::END_EXECUTE_FIXTURES_EVENT, [$this, 'endExecutorHandler']);
        $sharedEventManager->attach(RunFixtureEventSubscriberInterface::class, FixtureEvent::EXECUTE_FIXTURE_EVENT, [$this, 'executeFixtureHandler']);
    }

    /**
     * Обработчик события когда стартовал запуск фикстур
     *
     * @param FixtureExecutorEvent $e
     *
     * @throws \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function startExecutorHandler(FixtureExecutorEvent $e)
    {
        $executor = $e->getExecutor();
        if (!$executor instanceof ExecutorInterface) {
            $errMsg = 'Executor not specified';
            throw new Exception\RuntimeException($errMsg);
        }

        if (null !== $this->currentFixture) {
            $errMsg = 'There should not be running fixtures';
            throw new Exception\RuntimeException($errMsg);
        }

        if (null !== $this->currentExecutor) {
            $errMsg = sprintf(
                'You can not start the executor %s. Already running %s',
                $executor->getName(),
                $this->currentExecutor->getName()
            );
            throw new Exception\RuntimeException($errMsg);
        }


        $event = $this->buildEvent(
            ExecutorDispatcherEvent::RUN_EXECUTOR_EVENT,
            $executor,
            $executor,
            $e->getMethod()
        );

        $this->getEventManager()->trigger($event);

        $this->currentExecutor = $executor;
    }

    /**
     * Обработчик события когда фикстуры отработали
     *
     * @param FixtureExecutorEvent $e
     *
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     */
    public function endExecutorHandler(FixtureExecutorEvent $e)
    {
        $executor = $e->getExecutor();
        if (!$executor instanceof ExecutorInterface) {
            $errMsg = 'Executor not specified';
            throw new Exception\RuntimeException($errMsg);
        }

        if ($executor !== $this->currentExecutor) {
            $errMsg = 'You can not simultaneously run two Executor';
            throw new Exception\RuntimeException($errMsg);
        }

        $previousFixture = $this->currentFixture;

        $eventManager = $this->getEventManager();
        if (null !== $previousFixture) {
            $finishFixtureEvent = $this->buildEvent(
                ExecutorDispatcherEvent::FINISH_FIXTURE_EVENT,
                $executor,
                $previousFixture,
                $e->getMethod(),
                $previousFixture
            );

            $eventManager->trigger($finishFixtureEvent);
        }

        $event = $this->buildEvent(
            ExecutorDispatcherEvent::FINISH_EXECUTOR_EVENT,
            $executor,
            $executor,
            $e->getMethod()
        );


        $eventManager->trigger($event);



        $this->currentExecutor = null;
        $this->currentFixture = null;
    }


    /**
     * Обработчик события когда запускается фикстура
     *
     * @param FixtureEvent $e
     *
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
     */
    public function executeFixtureHandler(FixtureEvent $e)
    {
        if (null === $this->currentExecutor) {
            $errMsg = 'It failed to get the Executor, to the current context';
            throw new Exception\RuntimeException($errMsg);
        }

        $executor = $e->getExecutor();
        if (!$executor instanceof ExecutorInterface) {
            $errMsg = 'Executor not specified';
            throw new Exception\RuntimeException($errMsg);
        }

        if ($executor !== $this->currentExecutor) {
            $errMsg = 'Attempting to perform a fixture of incorrect executor';
            throw new Exception\RuntimeException($errMsg);
        }

        $previousFixture = $this->currentFixture;
        $currentFixture = $e->getFixture();
        if (!$currentFixture instanceof Fixture) {
            $errMsg = 'Fixture not specified';
            throw new Exception\RuntimeException($errMsg);
        }
        $this->currentFixture = $currentFixture;

        $eventManager = $this->getEventManager();
        if (null !== $previousFixture) {
            $finishFixtureEvent = $this->buildEvent(
                ExecutorDispatcherEvent::FINISH_FIXTURE_EVENT,
                $executor,
                $previousFixture,
                $e->getMethod(),
                $previousFixture
            );

            $eventManager->trigger($finishFixtureEvent);
        }

        $runFixtureEvent = $this->buildEvent(
            ExecutorDispatcherEvent::RUN_FIXTURE_EVENT,
            $executor,
            $currentFixture,
            $e->getMethod(),
            $currentFixture
        );

        $eventManager->trigger($runFixtureEvent);
    }

    /**
     * Собирает событие
     *
     * @param                   $eventName
     * @param ExecutorInterface $executor
     * @param                   $target
     * @param                   $method
     * @param Fixture|null      $fixture
     *
     * @return ExecutorDispatcherEvent
     */
    protected function buildEvent($eventName, ExecutorInterface $executor, $target, $method, Fixture $fixture = null)
    {
        $event = clone $this->getExecutorDispatcherEventPrototype();
        $event->setName($eventName);
        $event->setExecutor($executor);
        $event->setTarget($target);
        $event->setMethod($method);
        if (null !== $fixture) {
            $event->setFixture($fixture);
        }

        return $event;
    }

    /**
     * Возвращает прототип для события
     *
     * @return ExecutorDispatcherEvent
     */
    public function getExecutorDispatcherEventPrototype()
    {
        if (null === $this->executorDispatcherEventPrototype) {
            $executorDispatcherEventPrototype = new ExecutorDispatcherEvent();
            $this->setExecutorDispatcherEventPrototype($executorDispatcherEventPrototype);
        }
        return $this->executorDispatcherEventPrototype;
    }

    /**
     * Устанавливает прототип для события
     *
     * @param ExecutorDispatcherEvent $executorDispatcherEventPrototype
     *
     * @return $this
     */
    public function setExecutorDispatcherEventPrototype(ExecutorDispatcherEvent $executorDispatcherEventPrototype)
    {
        $this->executorDispatcherEventPrototype = $executorDispatcherEventPrototype;

        return $this;
    }

    /**
     * Подключение обработчиков по умолчанию
     *
     * @return void
     */
    public function attachDefaultListeners()
    {
        $eventManager = $this->getEventManager();

        $eventManager->attach(ExecutorDispatcherEvent::RUN_EXECUTOR_EVENT, [$this, 'onRunExecutorHandler'], 100);
        $eventManager->attach(ExecutorDispatcherEvent::RUN_FIXTURE_EVENT, [$this, 'onRunFixtureHandler'], 100);
        $eventManager->attach(ExecutorDispatcherEvent::FINISH_FIXTURE_EVENT, [$this, 'onFinishFixtureHandler'], 100);
        $eventManager->attach(ExecutorDispatcherEvent::FINISH_EXECUTOR_EVENT, [$this, 'onFinishExecutorHandler'], 100);
    }

    /**
     * Обработчик события возникающего при старте Executor'a
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     */
    public function onRunExecutorHandler(ExecutorDispatcherEvent $e)
    {
    }


    /**
     * Обработчик события возникающего при старте фикстуры
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     */
    public function onRunFixtureHandler(ExecutorDispatcherEvent $e)
    {
    }


    /**
     * Обработчик события возникающего при окончание работы фикстуры
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     */
    public function onFinishFixtureHandler(ExecutorDispatcherEvent $e)
    {
    }



    /**
     * Обработчик события возникающего при окончание работы Executor'a
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     */
    public function onFinishExecutorHandler(ExecutorDispatcherEvent $e)
    {
    }
}
