<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

/**
 * Interface ExecutorInterface
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
interface ExecutorInterface
{
    /**
     * Импортировать данные из фикстур в базу
     *
     * @return void
     */
    public function import();

    /**
     * Откатить данные на основе информации из фикстур
     *
     * @return void
     */
    public function purge();


    /**
     * Возвращает имя
     *
     * @return string
     */
    public function getName();
}
