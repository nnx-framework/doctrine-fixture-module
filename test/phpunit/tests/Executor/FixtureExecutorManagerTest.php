<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Executor;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManager;


/**
 * Class FixtureExecutorManagerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Executor
 */
class FixtureExecutorManagerTest extends AbstractHttpControllerTestCase
{

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToDefaultAppConfig()
        );

        parent::setUp();
    }

    /**
     * Проверка ситуации когда создаваемый executor не проходит валидацию
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     * @expectedExceptionMessage Plugin of type stdClass is invalid; must implement Nnx\DoctrineFixtureModule\Executor\ExecutorInterface
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testInvalidPlugin()
    {
        /** @var FixtureExecutorManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);
        $manager->setInvokableClass('test', \stdClass::class);

        $manager->get('test');
    }
}
