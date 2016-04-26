<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;


/**
 * Class ExecutorControllerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Controller
 */
class ExecutorControllerTest extends AbstractConsoleControllerTestCase
{

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getApplication()->getServiceManager()->get('doctrine.entitymanager.test');

        $tool = new SchemaTool($em);
        $tool->dropDatabase();

        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $tool->createSchema($metadata);

        parent::setUp();
    }


    /**
     *
     * @throws \Exception
     */
    public function testRunExecutorAction()
    {
        $this->dispatch('nnx:fixture import executor testFilterUsedFixture --default-object-manager=doctrine.entitymanager.test');
    }
}
