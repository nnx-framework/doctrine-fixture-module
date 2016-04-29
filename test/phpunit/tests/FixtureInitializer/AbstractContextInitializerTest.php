<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureInitializer;

use Nnx\DoctrineFixtureModule\FixtureInitializer\AbstractContextInitializer;
use PHPUnit_Framework_TestCase;

/**
 * Class AbstractContextInitializerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureInitializer
 */
class AbstractContextInitializerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractContextInitializer
     */
    protected $abstractContextInitializer;

    /**
     * Установка окружения
     *
     *
     * @return void
     * @throws \PHPUnit_Framework_Exception
     */
    public function setUp()
    {
        $this->abstractContextInitializer = $this->getMockForAbstractClass(AbstractContextInitializer::class);
        parent::setUp();
    }

    /**
     * Проврека ситуации когда происходит попытка получить данные контекста, в момент когда они еще не были установленны
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     * @expectedExceptionMessage Context not specified
     *
     * @return void
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    public function testNotSpecifiedContextData()
    {
        $this->abstractContextInitializer->getContextData();
    }


    /**
     * Проврека ситуации когда происходит попытка получить из данных контекста, не установленный параметр
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     * @expectedExceptionMessage Param invalidParam not found in context
     *
     * @return void
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    public function testNotSpecifiedContextParam()
    {
        $this->abstractContextInitializer->setContextData([]);
        $this->abstractContextInitializer->getContextParam('invalidParam');
    }
}
