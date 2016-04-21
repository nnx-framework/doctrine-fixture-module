<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Filter;

use Doctrine\ORM\Tools\SchemaTool;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;

/**
 * Class FilterUsedFixtureIntegrationTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Filter
 */
class FilterUsedFixtureIntegrationTest extends AbstractHttpControllerTestCase
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
     * Создаение Executor'a, используещего FilterUsedFixture
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testCreateExecuterWithTestedFilter()
    {
        /** @var FixtureExecutorManagerInterface $fixtureExecutorManager */
        $fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);
        $executor = $fixtureExecutorManager->get('testFilterUsedFixture');

        $executor->import();
        $executor->import();
    }
}
