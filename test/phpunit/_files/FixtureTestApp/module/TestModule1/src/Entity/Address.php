<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\Entity;


/**
 * Class TestEntity
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule\Entity
 *
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="dfm_test_address")
 */
class Address
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
     * @Doctrine\ORM\Mapping\Column(name="city", type="string")
     *
     * @var string
     */
    protected $city;

    /**
     * @Doctrine\ORM\Mapping\Column(name="street", type="string")
     *
     * @var string
     */
    protected $street;

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
     * Устанавливает название города
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Возращает название города
     *
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Возвращает название улицы
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Устанавливает возвращает название улицы
     *
     * @param string $street
     *
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }
}