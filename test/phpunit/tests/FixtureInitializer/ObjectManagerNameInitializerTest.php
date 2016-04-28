<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureInitializer;

use Doctrine\ORM\Tools\SchemaTool;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\TestInjectObjectManagerNameFixture;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\EventManager\EventInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class ObjectManagerNameInitializerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureInitializer
 */
class ObjectManagerNameInitializerTest extends AbstractHttpControllerTestCase
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
     * Проверка работы инъекции имени ObjectManager'a
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInjectObjectManagerName()
    {

        /** @var FixtureExecutorManagerInterface $fixtureExecutorManager */
        $fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);

        $sharedEventManager = $this->getApplication()->getEventManager()->getSharedManager();

        $actualObjectManagerName = null;

        $listener = $sharedEventManager->attach(
            TestInjectObjectManagerNameFixture::class,
            'testInjectObjectManagerName',
            function (EventInterface $e) use (&$actualObjectManagerName) {

                /** @var TestInjectObjectManagerNameFixture $target */
                $target = $e->getTarget();
                $actualObjectManagerName = $target->getObjectManagerName();

                $e->stopPropagation(true);
            }
        );

        $expectedObjectManagerName = 'testObjectManagerName';

        $fixtureExecutorManager->get('testInjectObjectManagerNameFixture')->import([
            'objectManagerName' => $expectedObjectManagerName
        ]);


        $sharedEventManager->detach(TestInjectObjectManagerNameFixture::class, $listener);

        static::assertEquals($expectedObjectManagerName, $actualObjectManagerName);
    }
}
