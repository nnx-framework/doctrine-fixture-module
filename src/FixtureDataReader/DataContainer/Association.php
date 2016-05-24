<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer;

/**
 * Class Associations
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer
 */
class Association
{
    /**
     * Имя ассоциаци
     *
     * @var string
     */
    protected $name;

    /**
     * Сущности на которые указывает связь
     *
     * @var Entity[]
     */
    protected $entities;

    /**
     * Хранилище индексов
     *
     * @var Index
     */
    protected $index;

    /**
     * Association constructor.
     *
     * @param Index $index
     */
    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    /**
     * Устанавливает имя ассоциаци
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Возвращает имя ассоциаци
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Добавляет контейнер с данными о вложенной сущности
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
     * Возвращает контейнер с данными о вложенных сущностях
     *
     * @return Entity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }
}
