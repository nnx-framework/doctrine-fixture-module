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
    protected $level;

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
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Устанавливает уровень на котором расположена сущность
     *
     * @param int $level
     *
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;

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

    /**
     * Определяет, есть ли связь, с заданным именем
     *
     * @param $name
     *
     * @return bool
     */
    public function hasAssociation($name)
    {
        return array_key_exists($name, $this->associations);
    }


    /**
     * Определяет, есть ли связь, с заданным именем
     *
     * @param $name
     *
     * @return bool
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\InvalidArgumentException
     */
    public function getAssociation($name)
    {
        if (!array_key_exists($name, $this->associations)) {
            $errMsg = sprintf('Association %s not found', $name);
            throw new Exception\InvalidArgumentException($errMsg);
        }

        return $this->associations[$name];
    }
}
