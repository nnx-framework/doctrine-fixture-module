<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader\SimpleXmlFormat;

use PHPUnit_Framework_TestCase;
use Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\ParserContext;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainerInterface;

/**
 * Class ParserContextTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader\SimpleXmlFormat
 */
class ParserContextTest extends PHPUnit_Framework_TestCase
{

    /**
     * Тестируемый компонент
     *
     * @var ParserContext
     */
    protected $parserContext;

    /**
     * Установка окружения
     *
     */
    public function setUp()
    {
        $this->parserContext = new ParserContext();
        parent::setUp();
    }

    /**
     * Проверка ситуации кога не указана родительский контейнер
     * 
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @expectedExceptionMessage Parent entity not specified
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     */
    public function testParentEntityNotSpecified()
    {
        $this->parserContext->setParentAssociation('parentAssociation');
        $this->parserContext->validate();
    }

    /**
     * Проверка ситуации кога не указана родительский контейнер
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @expectedExceptionMessage Parent association not specified
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testParentAssociationNotSpecified()
    {
        /** @var Entity $parentEntry */
        $parentEntry = $this->getMock(Entity::class);
        $this->parserContext->setParentEntity($parentEntry);
        $this->parserContext->validate();
    }


    /**
     * Проверка ситуации когда, не был передан объект для работы с xpath
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @expectedExceptionMessage Xpath object not specified
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testXpathObjectNotSpecified()
    {
        $this->parserContext->validate();
    }


    /**
     * Проверка ситуации когда, не был передан контейнер с данными
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @expectedExceptionMessage Data container not specified
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testDataContainerNotSpecified()
    {
        /** @var \DOMXPath $xpath */
        $xpath = $this->getMock(\DOMXPath::class, [], [], '', false);
        $this->parserContext->setXpath($xpath);
        $this->parserContext->validate();
    }


    /**
     * Проверка ситуации когда, не был объект для хранения индексов контейнеров с данными
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @expectedExceptionMessage Index storage not specified
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testIndexStorageNotSpecified()
    {
        /** @var \DOMXPath $xpath */
        $xpath = $this->getMock(\DOMXPath::class, [], [], '', false);
        $this->parserContext->setXpath($xpath);

        /** @var DataContainerInterface $dataContainer */
        $dataContainer = $this->getMock(DataContainerInterface::class);
        $this->parserContext->setDataContainer($dataContainer);

        $this->parserContext->validate();
    }
}
