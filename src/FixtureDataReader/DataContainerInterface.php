<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;

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
     * @return Entity[]
     */
    public function getEntities();

    /**
     * Добавляет информацию о данных для сущности
     *
     * @param Entity $entity
     *
     * @return $this
     */
    public function addEntity(Entity $entity);
}
