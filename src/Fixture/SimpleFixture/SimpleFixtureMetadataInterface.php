<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Association;

/**
 * Interface SimpleFixtureMetadataInterface
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
interface SimpleFixtureMetadataInterface
{

    /**
     * Связывает id контейнера с данными, с именем класса сущности
     *
     * @param Entity $dataItem
     * @param string $entityClassName
     *
     * @return $this
     */
    public function linkDataContainerToEntityClassName(Entity $dataItem, $entityClassName);

    /**
     * Добавляет id контейнера с данными, для которого стоит искать уже созданную сущность в базе данных
     *
     * @param Entity $dataItem
     *
     * @return $this
     */
    public function addCandidateForSearchInDb(Entity $dataItem);


    /**
     * Добавляет элемент в карту ассоциаций
     *
     * @param Entity $fromDataItem
     * @param Entity $toDataItem
     * @param        $association
     *
     * @return $this
     */
    public function addAssociationInfo(Entity $fromDataItem, Entity $toDataItem, Association $association);


    /**
     * Добавляет обратную связь
     *
     * @param Entity $fromDataItem
     * @param Entity $toDataItem
     * @param        $associationName
     *
     * @return $this
     */
    public function addReverseAssociationInfo(Entity $fromDataItem, Entity $toDataItem, $associationName);


    /**
     * Возвращает имя класса сущности, который привязан к контейнеру
     *
     * @param Entity $dataItem
     *
     * @return string
     */
    public function getEntityClassNameByDataContainer(Entity $dataItem);


    /**
     * Определяет, нужно ли искать сущность, для контейнера с данными в бд
     *
     * @param Entity $dataItem
     *
     * @return boolean
     */
    public function isCandidateForSearchInDb(Entity $dataItem);

    /**
     * Возвращает данные о асоцицих, на основе контейнера
     *
     * @param Entity $dataItem
     *
     * @return array
     */
    public function getAssociationsForEntity(Entity $dataItem);

    /**
     * Проверяет есть ли для контейнера с данными связанная ассоциация
     *
     * @param Entity $dataItem
     *
     * @return boolean
     */
    public function hasAssociationsForEntity(Entity $dataItem);
}
