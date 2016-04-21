<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener;

use Nnx\DoctrineFixtureModule\Event\ExecutorDispatcherEvent;

/**
 * Interface ExecutorDispatcherInterface
 *
 * @package Nnx\DoctrineFixtureModule\Listener
 */
interface ExecutorDispatcherInterface
{
    /**
     * Обработчик события возникающего при старте Executor'a
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     */
    public function onRunExecutorHandler(ExecutorDispatcherEvent $e);


    /**
     * Обработчик события возникающего при старте фикстуры
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     */
    public function onRunFixtureHandler(ExecutorDispatcherEvent $e);


    /**
     * Обработчик события возникающего при окончание работы фикстуры
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     */
    public function onFinishFixtureHandler(ExecutorDispatcherEvent $e);



    /**
     * Обработчик события возникающего при окончание работы Executor'a
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     */
    public function onFinishExecutorHandler(ExecutorDispatcherEvent $e);
}
