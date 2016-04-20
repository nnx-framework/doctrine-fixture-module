<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener;

use Doctrine\Common\EventSubscriber;
use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;


/**
 * Interface ExecuteEventSubscriberInterface
 *
 * @package Nnx\DoctrineFixtureModule\Listener
 */
interface ExecuteEventSubscriberInterface extends EventSubscriber
{
    /**
     * Возвращает Executor для фикстур
     *
     * @return ExecutorInterface
     *
     */
    public function getExecutor();

    /**
     * Устанавливает Executor для фикстур
     *
     * @param ExecutorInterface $executor
     *
     * @return $this
     */
    public function setExecutor(ExecutorInterface $executor);
}
