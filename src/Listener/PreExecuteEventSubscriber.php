<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener;

use Doctrine\Fixture\Event\BulkFixtureEvent;
use Doctrine\Fixture\Event\BulkImportFixtureEventListener;
use Doctrine\Fixture\Event\BulkPurgeFixtureEventListener;
use Nnx\DoctrineFixtureModule\Event\FixtureExecutorEvent;

/**
 * Class PreExecuteEventSubscriber
 *
 * @package Nnx\DoctrineFixtureModule\Listener
 */
class PreExecuteEventSubscriber
    extends AbstractExecuteEventSubscriber
    implements
        PreExecuteEventSubscriberInterface,
        BulkImportFixtureEventListener,
        BulkPurgeFixtureEventListener
{

    /**
     * Идендфикатор для менеджера событий
     *
     * @var array
     */
    protected $eventIdentifier = [
        PreExecuteEventSubscriberInterface::class
    ];

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            BulkImportFixtureEventListener::BULK_IMPORT,
            BulkPurgeFixtureEventListener::BULK_PURGE,
        ];
    }

    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function bulkImport(BulkFixtureEvent $event)
    {
        $workflowExecutorEvent = clone $this->getPrototypeEvent();
        $workflowExecutorEvent->setName(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT);
        $workflowExecutorEvent->setMethod(FixtureExecutorEvent::IMPORT);
        $executor = $this->getExecutor();
        $workflowExecutorEvent->setExecutor($executor);
        $workflowExecutorEvent->setTarget($executor);

        $this->getEventManager()->trigger($workflowExecutorEvent);
    }


    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function bulkPurge(BulkFixtureEvent $event)
    {
        $workflowExecutorEvent = clone $this->getPrototypeEvent();
        $workflowExecutorEvent->setName(FixtureExecutorEvent::START_EXECUTE_FIXTURES_EVENT);
        $workflowExecutorEvent->setMethod(FixtureExecutorEvent::PURGE);
        $executor = $this->getExecutor();
        $workflowExecutorEvent->setExecutor($executor);
        $workflowExecutorEvent->setTarget($executor);

        $this->getEventManager()->trigger($workflowExecutorEvent);
    }
}
