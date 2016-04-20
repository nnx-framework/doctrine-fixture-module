<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener;

use Doctrine\Common\EventSubscriber;
use Nnx\DoctrineFixtureModule\Event\FixtureExecutorEvent;
use Nnx\DoctrineFixtureModule\Executor\ExecutorAwareTrait;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * Class AbstractExecuteEventSubscriber
 *
 * @package Nnx\DoctrineFixtureModule\Listener
 */
abstract class AbstractExecuteEventSubscriber implements
    EventSubscriber,
    ExecuteEventSubscriberInterface
{
    use EventManagerAwareTrait, ExecutorAwareTrait;

    /**
     * Прототип для объекта события
     *
     * @var FixtureExecutorEvent
     */
    protected $prototypeEvent;

    /**
     * Возвращает прототип для объекта события
     *
     * @return FixtureExecutorEvent
     */
    public function getPrototypeEvent()
    {
        if (null === $this->prototypeEvent) {
            $prototypeEvent = new FixtureExecutorEvent();
            $this->setPrototypeEvent($prototypeEvent);
        }
        return $this->prototypeEvent;
    }

    /**
     * Устанавливает прототип для объекта события
     *
     * @param FixtureExecutorEvent $prototypeEvent
     *
     * @return $this
     */
    public function setPrototypeEvent($prototypeEvent)
    {
        $this->prototypeEvent = $prototypeEvent;

        return $this;
    }
}
