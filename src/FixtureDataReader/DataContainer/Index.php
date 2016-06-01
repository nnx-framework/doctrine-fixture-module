<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer;

/**
 * Class Index
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer
 */
class Index
{
    /**
     * Все контейнеры с даннными для фикстуры
     *
     * @var Entity[]
     */
    protected $entities = [];

    /**
     * Ключем является уровень вложеннесоти контейнера с данными, а значением массив контейнеров, расположенных на данном
     * уровен вложенности
     *
     * @var array
     */
    protected $levelToEntities = [];

    /**
     * Строит индекс для сущностей
     *
     * @param Entity $entity
     *
     * @return $this
     */
    public function indexEntity(Entity $entity)
    {
        $level = $entity->getLevel();
        if (!array_key_exists($level, $this->levelToEntities)) {
            $this->levelToEntities[$level] = [];
        }
        $this->levelToEntities[$level][] = $entity;

        $this->entities[$entity->getId()] = $entity;
        return $this;
    }

    /**
     * Возвращает список всех контейнеров с данными
     *
     * @return Entity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Возвращает контейнер с данными, на основе его id
     *
     * @param $id
     *
     * @return mixed
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\RuntimeException
     */
    public function getEntityById($id)
    {
        if (!array_key_exists($id, $this->entities)) {
            $errMsg = sprintf('Data container id: %s not found', $id);
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->entities[$id];
    }
}
