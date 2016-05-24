<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Fixture;

use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class SimpleFixtureTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Fixture
 */
class SimpleFixtureTest extends AbstractHttpControllerTestCase
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


    public function testImportSimpleFixture()
    {
        /** @var FixtureExecutorManagerInterface $fixtureExecutorManager */
        $fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);

        $executor = $fixtureExecutorManager->get('testSimpleFixture');

        $executor->import([
            'objectManagerName' => 'doctrine.entitymanager.test'
        ]);
    }
}
