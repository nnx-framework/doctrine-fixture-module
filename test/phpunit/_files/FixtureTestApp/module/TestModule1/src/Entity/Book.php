<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class TestEntity
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule\Entity
 *
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Table(name="dfm_test_book")
 */
class Book
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
     * @Doctrine\ORM\Mapping\Column(name="isbn", type="string")
     *
     * @var string
     */
    protected $isbn;

    /**
     * @Doctrine\ORM\Mapping\Column(name="date_of_publication", type="datetime")
     *
     * @var DateTimeInterface
     */
    protected $dateOfPublication;

    /**
     * Заголовок книги
     *
     * @Doctrine\ORM\Mapping\Column(name="title", type="string")
     *
     * @var string
     */
    protected $title;

    /**
     * Свяизь с авторами
     *
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Author", inversedBy="books")
     * @Doctrine\ORM\Mapping\JoinTable(name="authors_to_books")
     *
     * @var ArrayCollection
     */
    protected $authors;

    /**
     * Book constructor.
     *
     */
    public function __construct()
    {
        $this->authors = new ArrayCollection() ;
    }

    /**
     * @param Author $author
     *
     * @return $this
     */
    public function addAuthor(Author $author)
    {
        $this->authors[] = $author;

        return $this;
    }

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
     * Устанавливает isbn
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Возращает isbn
     *
     * @param string $isbn
     *
     * @return $this
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Возвращает дату публикации
     *
     * @return DateTimeInterface
     */
    public function getDateOfPublication()
    {
        return $this->dateOfPublication;
    }

    /**
     * Устанавливает датку публикации
     *
     * @param DateTimeInterface $dateOfPublication
     *
     * @return $this
     */
    public function setDateOfPublication(DateTimeInterface $dateOfPublication)
    {
        $this->dateOfPublication = $dateOfPublication;

        return $this;
    }

    /**
     * Заголовок книги
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Устанавливает заголовок книги
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Возвращает авторов книги
     *
     * @return ArrayCollection
     */
    public function getAuthors()
    {
        return $this->authors;
    }
}