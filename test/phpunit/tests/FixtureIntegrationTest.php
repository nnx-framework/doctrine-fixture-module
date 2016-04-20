<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;

/**
 * Class ModuleTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test
 */
class FixtureIntegrationTest extends AbstractHttpControllerTestCase
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
     * Проверка что модуль загружается
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testLoadModule()
    {
        /** @var  FixtureLoaderManagerInterface $fixtureLoaderManager */
        $fixtureLoaderManager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);

        $fixtureLoaderManager->get('testChainFixtureLoader');
    }
}
