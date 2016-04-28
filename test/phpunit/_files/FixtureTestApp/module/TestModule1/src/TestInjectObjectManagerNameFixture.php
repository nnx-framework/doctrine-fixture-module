<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1;


use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ObjectManagerNameAwareInterface;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ObjectManagerNameAwareTrait;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * Class TestInjectObjectManagerNameFixture
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1
 */
class TestInjectObjectManagerNameFixture implements Fixture, ObjectManagerNameAwareInterface
{
    use ObjectManagerNameAwareTrait;

    use EventManagerAwareTrait;

    /**
     * @inheritdoc
     *
     */
    public function import()
    {
        $this->getEventManager()->trigger('testInjectObjectManagerName', $this);
    }

    /**
     * @inheritdoc
     *
     */
    public function purge()
    {

    }

}