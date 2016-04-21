<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Filter;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManager;


/**
 * Class FixtureFilterManagerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Filter
 */
class FixtureFilterManagerTest extends AbstractHttpControllerTestCase
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
            include TestPaths::getPathToFixtureTestAppConfig()
        );

        parent::setUp();
    }

    /**
     * Проверка ситуации когда создаваемый фильтр не проходит валидацию
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Filter\Exception\RuntimeException
     * @expectedExceptionMessage Plugin of type stdClass is invalid; must implement Doctrine\Fixture\Filter\Filter
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testInvalidPlugin()
    {
        /** @var FixtureFilterManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(FixtureFilterManagerInterface::class);
        $manager->setInvokableClass('test', \stdClass::class);

        $manager->get('test');
    }
}
