<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader\DataContainer;

use PHPUnit_Framework_TestCase;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;

/**
 * Class EntityTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader\DataContainer
 */
class EntityTest extends PHPUnit_Framework_TestCase
{

    /**
     * Тестируемый компонент
     *
     * @var Entity
     */
    protected $entity;

    /**
     * Установка окружения
     *
     */
    public function setUp()
    {
        $this->entity = new Entity();
        parent::setUp();
    }

    /**
     * Проверка поведения компонента, когда происходит попытка получить не существующую связь
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\InvalidArgumentException
     * @expectedExceptionMessage Association InvalidAssociation not found
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\InvalidArgumentException
     */
    public function testGetEntityByInvalidId()
    {
        $this->entity->getAssociation('InvalidAssociation');
    }
}
