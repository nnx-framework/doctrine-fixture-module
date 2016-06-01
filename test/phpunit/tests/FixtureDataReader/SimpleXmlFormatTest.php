<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use PHPUnit_Framework_TestCase;
use Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat;

/**
 * Class SimpleXmlFormatTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader
 */
class SimpleXmlFormatTest extends PHPUnit_Framework_TestCase
{

    /**
     * Тестируемый компонент
     *
     * @var SimpleXmlFormat
     */
    protected $simpleXmlFormat;

    /**
     * Установка окружения
     *
     */
    public function setUp()
    {
        $this->simpleXmlFormat = new SimpleXmlFormat();
        parent::setUp();
    }

    /**
     * Попытка в качестве ресурса указать не существующий файл
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\InvalidResourceException
     * @expectedExceptionMessage Invalid resource for fixture data: invalidFile
     *
     * @return void
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\InvalidResourceException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\InvalidArgumentException
     */
    public function testInvalidResource()
    {
        $this->simpleXmlFormat->loadDataFromResource('invalidFile');
    }

    /**
     * Проверка чтения некорректного xml файла
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\InvalidResourceException
     * @expectedExceptionMessage Premature end of data in tag eeeee line 1
     *
     * @return void
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\InvalidResourceException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\InvalidArgumentException
     */
    public function testInvalidXmlFile()
    {
        $path = TestPaths::getPathToSimpleXmlFormatDataReaderDataDir() . '/invalid_xml_file.xml';
        $this->simpleXmlFormat->loadDataFromResource($path);
    }

    /**
     * Проверка случая, когда данные фикстуры содержат дублирующиеся свойства
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\RuntimeException
     * @expectedExceptionMessage Property isbn already exists
     *
     * @return void
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\InvalidResourceException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\InvalidArgumentException
     */
    public function testDuplicateProperty()
    {
        $path = TestPaths::getPathToSimpleXmlFormatDataReaderDataDir() . '/duplicate_tag_file.xml';
        $this->simpleXmlFormat->loadDataFromResource($path);
    }
}
