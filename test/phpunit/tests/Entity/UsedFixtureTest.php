<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Entity;

use Nnx\DoctrineFixtureModule\Entity\UsedFixture;
use PHPUnit_Framework_TestCase;

/**
 * Class UsedFixtureTest
 *
 * @package Nnx\DoctrineFixtureModule\Entity
 */
class UsedFixtureTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UsedFixture
     */
    protected $usedFixture;


    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->usedFixture = new UsedFixture();
        parent::setUp();
    }

    /**
     * Проверка установки и получения id сущности
     *
     */
    public function testSetterGetterId()
    {
        $id = 7;
        static::assertEquals($this->usedFixture, $this->usedFixture->setId($id));
        static::assertEquals($id, $this->usedFixture->getId());
    }


    /**
     * Проверка установки и получения имени Executor'a
     *
     */
    public function testSetterGetterExecutorName()
    {
        $executorName = 'testExecutorName';
        static::assertEquals($this->usedFixture, $this->usedFixture->setExecutorName($executorName));
        static::assertEquals($executorName, $this->usedFixture->getExecutorName());
    }


    /**
     * Проверка установки и получения имени класса фикстуры
     *
     */
    public function testSetterGetterFixtureClassName()
    {
        $fixtureClassName = \stdClass::class;
        static::assertEquals($this->usedFixture, $this->usedFixture->setFixtureClassName($fixtureClassName));
        static::assertEquals($fixtureClassName, $this->usedFixture->getFixtureClassName());
    }
}
