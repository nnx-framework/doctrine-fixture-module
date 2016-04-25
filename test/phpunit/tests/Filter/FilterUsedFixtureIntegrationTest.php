<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Filter;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Fixture\Fixture;
use Doctrine\ORM\Tools\SchemaTool;
use Nnx\DoctrineFixtureModule\Entity\UsedFixture;
use Nnx\DoctrineFixtureModule\Event\ExecutorDispatcherEvent;
use Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Nnx\DoctrineFixtureModule\Listener\ExecutorDispatcherInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Exception\ExceptionInterface as ModuleExceptionInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp;


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
     * Проверка создать фильтр, без передачи ему контекста
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Filter\Exception\RuntimeException
     * @expectedExceptionMessage Context executor not specified
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNoContextForCreateFilterUsedFixture()
    {
        /** @var FixtureFilterManagerInterface $fixtureFilterManager */
        $fixtureFilterManager = $this->getApplicationServiceLocator()->get(FixtureFilterManagerInterface::class);

        try {
            $fixtureFilterManager->get(FilterUsedFixture::class);
        } catch (\Exception $e) {
            $currentException = $e;
            while (null !== $currentException && !$currentException instanceof ModuleExceptionInterface) {
                $currentException = $currentException->getPrevious();
            }

            throw $currentException;
        }
    }


    /**
     * Создаение Executor'a, используещего FilterUsedFixture
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     * @throws \UnexpectedValueException
     */
    public function testCreateExecutorWithTestedFilter()
    {
        /** @var FixtureExecutorManagerInterface $fixtureExecutorManager */
        $fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);
        $executorName = 'testFilterUsedFixture';
        $executor = $fixtureExecutorManager->get($executorName);

        $listUsedFixtures = [];

        $sharedEventManager = $this->getApplication()->getEventManager()->getSharedManager();
        $sharedEventManager->attach(
            ExecutorDispatcherInterface::class,
            ExecutorDispatcherEvent::FINISH_FIXTURE_EVENT,
            function (ExecutorDispatcherEvent $e) use (&$listUsedFixtures) {
                $listUsedFixtures[] = get_class($e->getFixture());
            }
        );

        $executor->import();
        $expectedUsedFixtures = [
            FixtureTestApp\TestModule1\FooFixture::class,
            FixtureTestApp\TestModule1\BarFixture::class,
            FixtureTestApp\FixturesDir\BarFixture::class,
            FixtureTestApp\FixturesDir\FooFixture::class,
            FixtureTestApp\TestModule1\BazFixture::class
        ];

        $firstRunUsedFixtures = $listUsedFixtures;
        static::assertEquals($expectedUsedFixtures, $firstRunUsedFixtures);

        /** @var ObjectManager $em */
        $em = $this->getApplicationServiceLocator()->get('doctrine.entitymanager.orm_default');

        /** @var UsedFixture[] $usedFixtureInfo */
        $usedFixtureInfo = $em->getRepository(UsedFixture::class)->findBy([
            'executorName' => $executorName
        ]);

        static::assertCount(count($expectedUsedFixtures), $usedFixtureInfo);

        foreach ($usedFixtureInfo as $usedFixture) {
            static::assertTrue(in_array($usedFixture->getFixtureClassName(), $expectedUsedFixtures, true));
        }

        $listUsedFixtures = [];

        $executor->import();

        static::assertEmpty($listUsedFixtures);
    }

    /**
     * Проверка ситуации когда происходит попытка пометить уже помеченную фикстуру как выполненную
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \UnexpectedValueException
     */
    public function testDuplicateFixtures()
    {
        /** @var FixtureExecutorManagerInterface $fixtureExecutorManager */
        $fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);
        $executorName = 'testDuplicateFixtures';
        $executor = $fixtureExecutorManager->get('testDuplicateFixtures');
        $executor->import();

        $actualFixtures = $executor->getLoader()->load();

        $expectedFixtures = array_map(function (Fixture $fixture) {
            return get_class($fixture);
        }, $actualFixtures);

        $expectedFixtures = array_unique($expectedFixtures);

        /** @var ObjectManager $em */
        $em = $this->getApplicationServiceLocator()->get('doctrine.entitymanager.orm_default');

        /** @var UsedFixture[] $usedFixtureInfo */
        $usedFixtureInfo = $em->getRepository(UsedFixture::class)->findBy([
            'executorName' => $executorName
        ]);

        static::assertCount(count($expectedFixtures), $usedFixtureInfo);

        foreach ($usedFixtureInfo as $usedFixture) {
            static::assertTrue(in_array($usedFixture->getFixtureClassName(), $expectedFixtures, true));
        }
    }
}
