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
        return $this;
    }
}
