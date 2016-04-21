<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Doctrine\Fixture\Loader\Loader;
use Doctrine\Fixture\Filter\Filter;

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

    /**
     * Возвращает загрузчик фикстур
     *
     * @return Loader
     */
    public function getLoader();


    /**
     * Возвращает фильтр фикстур
     *
     * @return Filter
     */
    public function getFilter();
}
