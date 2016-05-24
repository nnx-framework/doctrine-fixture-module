<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

/**
 * Interface DataContainerInterface
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader
 */
interface DataContainerInterface
{

    /**
     * Возвращает список контейнеров с данными для заполнения бд
     *
     * @return DataContainer\Entity[]
     */
    public function getEntities();
}
