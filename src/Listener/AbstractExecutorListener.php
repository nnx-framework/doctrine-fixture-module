<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener;

use Nnx\DoctrineFixtureModule\Event\FixtureExecutorEvent;
use Nnx\DoctrineFixtureModule\Event\FixtureEvent;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

/**
 * Class AbstractExecutorListener
 *
 * @package Nnx\DoctrineFixtureModule\Listener
 */
abstract class AbstractExecutorListener extends AbstractListenerAggregate
{
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
     */
    public function startExecutorHandler(FixtureExecutorEvent $e)
    {
        return;
    }

    /**
     * Обработчик события когда фикстуры отработали
     *
     * @param FixtureExecutorEvent $e
     */
    public function endExecutorHandler(FixtureExecutorEvent $e)
    {
        return;
    }


    /**
     * Обработчик события когда запускается фикстура
     *
     * @param FixtureEvent $e
     */
    public function executeFixtureHandler(FixtureEvent $e)
    {
        return;
    }
}
