<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener;

use Doctrine\Fixture\Event\FixtureEvent;
use Doctrine\Fixture\Event\ImportFixtureEventListener;
use Doctrine\Fixture\Event\PurgeFixtureEventListener;
use Nnx\DoctrineFixtureModule\Event\FixtureEvent as ExecuteFixtureEvent;
use Nnx\DoctrineFixtureModule\Executor\ExecutorAwareTrait;
use Zend\EventManager\EventManagerAwareTrait;


/**
 * Class RunFixtureEventSubscriber
 *
 * @package Nnx\DoctrineFixtureModule\Listener
 */
class RunFixtureEventSubscriber
    implements
        RunFixtureEventSubscriberInterface,
        ExecuteEventSubscriberInterface,
        ImportFixtureEventListener,
        PurgeFixtureEventListener
{

    use EventManagerAwareTrait, ExecutorAwareTrait;

    /**
     * Идендфикатор для менеджера событий
     *
     * @var array
     */
    protected $eventIdentifier = [
        RunFixtureEventSubscriberInterface::class
    ];
    /**
     * Прототип для объекта события
     *
     * @var ExecuteFixtureEvent
     */
    protected $prototypeEvent;

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            ImportFixtureEventListener::IMPORT,
            PurgeFixtureEventListener::PURGE,
        ];
    }

    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function import(FixtureEvent $event)
    {
        $workflowExecutorEvent = clone $this->getPrototypeEvent();
        $workflowExecutorEvent->setName(ExecuteFixtureEvent::EXECUTE_FIXTURE_EVENT);
        $workflowExecutorEvent->setMethod(ExecuteFixtureEvent::IMPORT);
        $executor = $this->getExecutor();
        $workflowExecutorEvent->setExecutor($executor);
        $workflowExecutorEvent->setTarget($executor);
        $workflowExecutorEvent->setFixture($event->getFixture());

        $this->getEventManager()->trigger($workflowExecutorEvent);
    }

    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function purge(FixtureEvent $event)
    {
        $workflowExecutorEvent = clone $this->getPrototypeEvent();
        $workflowExecutorEvent->setName(ExecuteFixtureEvent::EXECUTE_FIXTURE_EVENT);
        $workflowExecutorEvent->setMethod(ExecuteFixtureEvent::PURGE);
        $executor = $this->getExecutor();
        $workflowExecutorEvent->setExecutor($executor);
        $workflowExecutorEvent->setTarget($executor);
        $workflowExecutorEvent->setFixture($event->getFixture());

        $this->getEventManager()->trigger($workflowExecutorEvent);
    }



    /**
     * Возвращает прототип для объекта события
     *
     * @return ExecuteFixtureEvent
     */
    public function getPrototypeEvent()
    {
        if (null === $this->prototypeEvent) {
            $prototypeEvent = new ExecuteFixtureEvent();
            $this->setPrototypeEvent($prototypeEvent);
        }
        return $this->prototypeEvent;
    }

    /**
     * Устанавливает прототип для объекта события
     *
     * @param ExecuteFixtureEvent $prototypeEvent
     *
     * @return $this
     */
    public function setPrototypeEvent(ExecuteFixtureEvent $prototypeEvent)
    {
        $this->prototypeEvent = $prototypeEvent;

        return $this;
    }
}
