<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Index;

/**
 * Class DataContainer
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader
 */
class DataContainer implements DataContainerInterface
{
    /**
     * @var Entity[]
     */
    protected $entities = [];

    /**
     * Хранилище индексов
     *
     * @var Index
     */
    protected $index;

    /**
     * DataContainer constructor.
     *
     * @param Index $index
     */
    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    /**
     * Добавляет информацию о данных для сущности
     *
     * @param Entity $entity
     *
     * @return $this
     */
    public function addEntity(Entity $entity)
    {
        $this->index->indexEntity($entity);
        $this->entities[] = $entity;

        return $this;
    }

    /**
     * Возвращает список контейнеров с данными для заполнения бд
     *
     * @return DataContainer\Entity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Возвращает хранилище индексов
     *
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
    }
}
