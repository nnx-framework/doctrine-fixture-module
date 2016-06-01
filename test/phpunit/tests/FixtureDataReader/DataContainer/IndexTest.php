<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader\DataContainer;

use PHPUnit_Framework_TestCase;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Index;


/**
 * Class IndexTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader\DataContainer
 */
class IndexTest extends PHPUnit_Framework_TestCase
{

    /**
     * Тестируемый компонент
     *
     * @var Index
     */
    protected $index;

    /**
     * Установка окружения
     *
     */
    public function setUp()
    {
        $this->index = new Index();
        parent::setUp();
    }

    /**
     * Проверка поведения компонента, когда происходит попытка получить контейнер с данными по несуществующему id
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\RuntimeException
     * @expectedExceptionMessage Data container id: invalidId not found
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\RuntimeException
     */
    public function testGetEntityByInvalidId()
    {
        $this->index->getEntityById('invalidId');
    }
}
