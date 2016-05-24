<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer;

/**
 * Class Entity
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer
 */
class Entity
{
    /**
     * Уровень на котором расположенна сущность
     *
     * @var integer
     */
    protected $entityLevel;

    /**
     * Свойства сущности
     *
     * @var Property[]
     */
    protected $properties = [];

    /**
     * Связи с другими сущностями
     *
     * @var Association[]
     */
    protected $associations = [];

    /**
     * Родительская сущность
     *
     * @var Entity|null
     */
    protected $parentEntity;

    /**
     * Возвращает уровень, на котором расположена сущность
     *
     * @return int
     */
    public function getEntityLevel()
    {
        return $this->entityLevel;
    }

    /**
     * Устанавливает уровень на котором расположена сущность
     *
     * @param int $entityLevel
     *
     * @return $this
     */
    public function setEntityLevel($entityLevel)
    {
        $this->entityLevel = $entityLevel;

        return $this;
    }

    /**
     * Возвращает родительскую сущность
     *
     * @return Entity|null
     */
    public function getParentEntity()
    {
        return $this->parentEntity;
    }

    /**
     * Устанавливает родительскую сущность
     *
     * @param Entity $parentEntity
     *
     * @return $this
     */
    public function setParentEntity(Entity $parentEntity = null)
    {
        $this->parentEntity = $parentEntity;

        return $this;
    }

    /**
     * Добавляет связь на вложенную сущность
     *
     * @param Association $association
     *
     * @return $this
     */
    public function addAssociation(Association $association)
    {
        $this->associations[$association->getName()] = $association;

        return $this;
    }

    /**
     * Возвращает связи
     *
     * @return Association[]
     */
    public function getAssociations()
    {
        return $this->associations;
    }

    /**
     * Добавляет поле
     *
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property)
    {
        $this->properties[$property->getName()] = $property;

        return $this;
    }

    /**
     * Возвращает набор полей сущности
     *
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
