<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule\Entity;

/**
 * Class TestEntity
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule\Entity
 *
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="doctrine_fixture_module_simple_fixture")
 */
class TestEntity
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
     * @Doctrine\ORM\Mapping\Column(name="field_1", type="string")
     *
     * @var string
     */
    protected $field1;


    /**
     * @Doctrine\ORM\Mapping\Column(name="field_2", type="string")
     *
     * @var string
     */
    protected $field2;

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (integer)$id;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getField1()
    {
        return $this->field1;
    }

    /**
     *
     * @param string $field1
     *
     * @return $this
     */
    public function setField1($field1)
    {
        $this->field1 = $field1;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getField2()
    {
        return $this->field2;
    }

    /**
     *
     * @param string $field2
     *
     * @return $this
     */
    public function setField2($field2)
    {
        $this->field2 = $field2;

        return $this;
    }
}