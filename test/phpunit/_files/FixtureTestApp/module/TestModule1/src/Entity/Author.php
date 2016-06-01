<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Author
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\Entity
 *
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="dfm_test_author")
 */
class Author
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
     * Имя
     *
     * @var string
     */
    protected $name;

    /**
     * Фамилия
     *
     * @var string
     */
    protected $familyName;

    /**
     * Адрес
     *
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Address")
     * @Doctrine\ORM\Mapping\JoinColumn(name="address_id", referencedColumnName="id")
     *
     * @var Address
     */
    protected $address;

    /**
     * Книги которые написал автор
     *
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Book", mappedBy="authors")
     *
     * @var Book
     */
    protected $books;

    /**
     * Book constructor.
     *
     */
    public function __construct()
    {
        $this->books = new ArrayCollection() ;
    }

    /**
     * @param Book $book
     *
     * @return $this
     */
    public function addBook(Book $book)
    {
        $this->books[] = $book;

        return $this;
    }

    /**
     * Устанавливает имя
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Возвращает имя
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
     * Возвращает фамилию
     *
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Устанавливает фамилию
     *
     * @param string $familyName
     *
     * @return $this
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * Возвращает адресс
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Устанавливает адресс
     *
     * @param Address $address
     *
     * @return $this
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Book
     */
    public function getBooks()
    {
        return $this->books;
    }
}