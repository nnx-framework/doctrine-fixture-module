<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Fixture;

use Doctrine\ORM\Tools\SchemaTool;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Nnx\ZF2TestToolkit\Utils\OverrideAppConfigTrait;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class SimpleFixtureTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Fixture
 */
class SimpleFixtureTest extends AbstractHttpControllerTestCase
{
    use OverrideAppConfigTrait;

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Doctrine\ORM\Tools\ToolsException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
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


//        $schemaValidator = new SchemaValidator($em);
//        $errorsValidate = $schemaValidator->validateMapping();
        parent::setUp();
    }


    public function testImportSimpleFixture()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getApplication()->getServiceManager()->get('doctrine.entitymanager.test');
        $address = new \Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\Entity\Address();
        $address->setStreet('DefaultStreet')->setCity('DefaultCity');
        $em->persist($address);
        $em->flush();


        /** @var FixtureExecutorManagerInterface $fixtureExecutorManager */
        $fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);

        $executor = $fixtureExecutorManager->get('testSimpleFixture');

        $executor->import([
            'objectManagerName' => 'test'
        ]);
    }
}
