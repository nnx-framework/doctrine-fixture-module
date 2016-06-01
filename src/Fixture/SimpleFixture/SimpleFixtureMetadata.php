<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Association;

/**
 * Class SimpleFixtureMetadata
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
class SimpleFixtureMetadata implements SimpleFixtureMetadataInterface
{
    /**
     * Ассоциция ведет только на одну сущность
     *
     * @var string
     */
    const SINGLE_VALUE_ASSOCIATION = 'singleValuedAssociation';

    /**
     * Ассоциция ведет только на одну сущность
     *
     * @var string
     */
    const COLLECTION_VALUE_ASSOCIATION = 'collectionValuedAssociation';

    /**
     * Ключем является id контейнера с данными для фикстуры, а значением является имя класса сущности
     *
     * @var array
     */
    protected $dataContainerIdToEntityClassName = [];

    /**
     * id контейнера с данными, для которого стоит искать уже созданную сущность в базе данных
     *
     * @var array
     */
    protected $candidatesForSearchInDb = [];

    /**
     * Ключем является id контейнера с данными, а значением массив со следующей структурой:
     * имя ассоциации => массив с id контейнеров, на которые ведет ассоциация
     *
     * @var array
     */
    protected $associationMap = [];

    /**
     * Добавляет элемент в карту ассоциаций
     *
     * @param Entity $fromDataItem
     * @param Entity $toDataItem
     * @param        $association
     *
     * @return $this
     */
    public function addAssociationInfo(Entity $fromDataItem, Entity $toDataItem, Association $association)
    {
        $fromDataItemId = $fromDataItem->getId();
        if (!array_key_exists($fromDataItemId, $this->associationMap)) {
            $this->associationMap[$fromDataItemId] = [];
        }
        $associationName = $association->getName();
        if (!array_key_exists($associationName, $this->associationMap[$fromDataItemId])) {
            $this->associationMap[$fromDataItemId][$associationName] = [];
        }
        $toDataItemId = $toDataItem->getId();
        $this->associationMap[$fromDataItemId][$associationName][$toDataItemId] = $toDataItemId;

        return $this;
    }

    /**
     * Добавляет обратную связь
     *
     * @param Entity $fromDataItem
     * @param Entity $toDataItem
     * @param        $associationName
     *
     * @return $this
     */
    public function addReverseAssociationInfo(Entity $fromDataItem, Entity $toDataItem, $associationName)
    {
        $fromDataItemId = $fromDataItem->getId();
        if (!array_key_exists($fromDataItemId, $this->associationMap)) {
            $this->associationMap[$fromDataItemId] = [];
        }
        if (!array_key_exists($associationName, $this->associationMap[$fromDataItemId])) {
            $this->associationMap[$fromDataItemId][$associationName] = [];
        }
        $toDataItemId = $toDataItem->getId();
        $this->associationMap[$fromDataItemId][$associationName][$toDataItemId] = $toDataItemId;

        return $this;
    }

    /**
     * Связывает id контейнера с данными, с именем класса сущности
     *
     * @param Entity $dataItem
     * @param string $entityClassName
     *
     * @return $this
     */
    public function linkDataContainerToEntityClassName(Entity $dataItem, $entityClassName)
    {
        $this->dataContainerIdToEntityClassName[$dataItem->getId()] = $entityClassName;

        return $this;
    }

    /**
     * Добавляет id контейнера с данными, для которого стоит искать уже созданную сущность в базе данных
     * 
     * @param Entity $dataItem
     *
     * @return $this
     */
    public function addCandidateForSearchInDb(Entity $dataItem)
    {
        $this->candidatesForSearchInDb[$dataItem->getId()] = $dataItem->getId();

        return $this;
    }

    /**
     * Возвращает имя класса сущности, который привязан к контейнеру
     *
     * @param Entity $dataItem
     *
     * @return string
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     */
    public function getEntityClassNameByDataContainer(Entity $dataItem)
    {
        $dataItemId = $dataItem->getId();
        if (!array_key_exists($dataItemId, $this->dataContainerIdToEntityClassName)) {
            $errMsg = sprintf('Entity class name not found for data container:#id %s', $dataItemId);
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->dataContainerIdToEntityClassName[$dataItemId];
    }

    /**
     * Определяет, нужно ли искать сущность, для контейнера с данными в бд
     *
     * @param Entity $dataItem
     *
     * @return boolean
     */
    public function isCandidateForSearchInDb(Entity $dataItem)
    {
        return array_key_exists($dataItem->getId(), $this->candidatesForSearchInDb);
    }

    /**
     * Возвращает данные о асоцицих, на основе контейнера
     *
     * @param Entity $dataItem
     *
     * @return array
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     */
    public function getAssociationsForEntity(Entity $dataItem)
    {
        $dataItemId = $dataItem->getId();
        if (!array_key_exists($dataItemId, $this->associationMap)) {
            $errMsg = sprintf('Associations for data container id: %s not found', $dataItemId);
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->associationMap[$dataItemId];
    }

    /**
     * Проверяет есть ли для контейнера с данными связанная ассоциация
     *
     * @param Entity $dataItem
     *
     * @return boolean
     */
    public function hasAssociationsForEntity(Entity $dataItem)
    {
        return array_key_exists($dataItem->getId(), $this->associationMap);
    }
}
