<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Loader;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManager;


/**
 * Class FixtureLoaderManagerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test
 */
class FixtureLoaderManagerTest extends AbstractHttpControllerTestCase
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
     * Проверка ситуации когда создаваемый loader не проходит валидацию
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Loader\Exception\RuntimeException
     * @expectedExceptionMessage Plugin of type stdClass is invalid; must implement Doctrine\Fixture\Loader\Loader
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testInvalidPlugin()
    {
        /** @var FixtureLoaderManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);
        $manager->setInvokableClass('test', \stdClass::class);

        $manager->get('test');
    }
}
