<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Entity;

/**
 * Class UsedFixture
 *
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="doctrine_fixture_module_used_fixture")
 *
 * @package Nnx\DoctrineFixtureModule\Entity
 */
class UsedFixture
{
    /**
     * Идендфикатор сущности
     *
     * @var int
     *
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\Column(name="id", type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Имя Executor'a
     *
     * @Doctrine\ORM\Mapping\Column(name="executor_name", type="string")
     *
     * @var string
     */
    protected $executorName;


    /**
     * Имя класса фикстуры
     *
     * @Doctrine\ORM\Mapping\Column(name="fixture_class_name", type="string")
     *
     * @var string
     */
    protected $fixtureClassName;

    /**
     * Возвращает идендфикатор сущности
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Устанавливает идендфикатор сущности
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Возвращает имя Executor'a
     *
     * @return string
     */
    public function getExecutorName()
    {
        return $this->executorName;
    }

    /**
     * Устанавливает имя Executor'a
     *
     * @param string $executorName
     *
     * @return $this
     */
    public function setExecutorName($executorName)
    {
        $this->executorName = $executorName;

        return $this;
    }

    /**
     * Устанавливает имя класс фикстуры
     *
     * @return string
     */
    public function getFixtureClassName()
    {
        return $this->fixtureClassName;
    }

    /**
     * Возвращает имя класса фикстуры
     *
     * @param string $fixtureClassName
     *
     * @return $this
     */
    public function setFixtureClassName($fixtureClassName)
    {
        $this->fixtureClassName = $fixtureClassName;

        return $this;
    }
}
