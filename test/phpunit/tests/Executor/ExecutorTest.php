<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\Executor;

use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorBuilderInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Executor\Executor;
use Doctrine\Fixture\Configuration;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Doctrine\Fixture\Loader\Loader as FixtureLoaderInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManager;
use Doctrine\Fixture\Filter\Filter as FixtureFilterInterface;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManager;
use Doctrine\Fixture\Filter\ChainFilter;
use Doctrine\Fixture\Loader\ChainLoader;
use Doctrine\Fixture\Executor as FixtureExecutor;
use Doctrine\Fixture\Fixture as FixtureInterface;
use PHPUnit_Framework_MockObject_MockObject;


/**
 * Class ExecutorTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\Executor
 */
class ExecutorTest extends AbstractHttpControllerTestCase
{

    /**
     * Менеджер для работы с компонентами отвечающих за исполнение фикстур
     *
     * @var FixtureExecutorManagerInterface
     */
    protected $fixtureExecutorManager;

    /**
     * Установка окружения
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     *
     * @return void
     */
    public function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );
        parent::setUp();

        $this->fixtureExecutorManager = $this->getApplicationServiceLocator()->get(FixtureExecutorManagerInterface::class);
    }

    /**
     * Проверка создания Executor'a
     *
     * @return void
     */
    public function testCreateExecutor()
    {
        $executor = $this->fixtureExecutorManager->get(Executor::class);
        static::assertInstanceOf(Executor::class, $executor);
    }


    /**
     * Проверка создания Executor'a, со своей реализацией класса отвечающего за создание компонента Doctrine\Fixture\Executor. При этом,
     * builder получается как служба, из ServiceLocator приложения
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     *
     * @return void
     */
    public function testCreateExecutorWithCustomBuilder()
    {
        /** @var FixtureExecutorBuilderInterface $builderMock */
        $builderMock = $this->getMock(FixtureExecutorBuilderInterface::class);
        $testBuilderName = 'testBuilder';
        $this->getApplicationServiceLocator()->setService($testBuilderName, $builderMock);

        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(
            Executor::class,
            [
                'builder' => $testBuilderName
            ]
        );

        static::assertEquals($builderMock, $executor->getFixtureExecutorBuilder());
    }


    /**
     * Проверка создания Executor'a, со своей реализацией класса отвечающего за создание компонента Doctrine\Fixture\Executor. При создание
     * указывается класс builder'a
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     *
     * @return void
     */
    public function testCreateExecutorWithCustomClassBuilder()
    {
        /** @var FixtureExecutorBuilderInterface $builderMock */
        $builderMock = $this->getMock(FixtureExecutorBuilderInterface::class);
        $builderClassName = get_class($builderMock);

        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(
            Executor::class,
            [
                'builder' => $builderClassName
            ]
        );

        static::assertInstanceOf($builderClassName, $executor->getFixtureExecutorBuilder());
    }


    /**
     * Проверка ситуации когда при создании Executor'a указана не корректное имя Builder'a
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     * @expectedExceptionMessage Invalid fixture executor builder
     *
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Exception
     *
     * @return void
     */
    public function testCreateExecutorWithInvalidCustomBuilder()
    {
        $builderClassName = 'invalidClassName';

        try {
            $this->fixtureExecutorManager->get(
                Executor::class,
                [
                    'builder' => $builderClassName
                ]
            );
        } catch (ServiceNotCreatedException $e) {
            throw $e->getPrevious();
        }
    }

    /**
     * Проверка создания Executor'a, со своей реализацией класса отвечающего за конфигурацию Executor'a. При этом,
     * конфигурация получается как служба, из ServiceLocator приложения
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     *
     * @return void
     */
    public function testCreateExecutorWithCustomServiceConfiguration()
    {
        /** @var Configuration $configurationMock */
        $configurationMock = $this->getMock(Configuration::class);
        $testConfigurationName = 'testConfiguration';
        $this->getApplicationServiceLocator()->setService($testConfigurationName, $configurationMock);

        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(
            Executor::class,
            [
                'configuration' => $testConfigurationName
            ]
        );

        static::assertEquals($configurationMock, $executor->getConfiguration());
    }


    /**
     * Проверка создания Executor'a, со своей реализацией класса отвечающего за конфигурацию Executor'a. При создание
     * указывается класс конфигурации
     *
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     *
     * @return void
     */
    public function testCreateExecutorWithCustomClassConfiguration()
    {
        /** @var Configuration $configurationMock */
        $configurationMock = $this->getMock(Configuration::class);
        $configurationClassName = get_class($configurationMock);

        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(
            Executor::class,
            [
                'configuration' => $configurationClassName
            ]
        );

        static::assertInstanceOf($configurationClassName, $executor->getConfiguration());
    }



    /**
     * Проверка создания Executor'a, с указанием его имени
     *
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     *
     * @return void
     */
    public function testCreateExecutorWithCustomName()
    {
        $name = 'test_name';
        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(
            Executor::class,
            [
                'name' => $name
            ]
        );

        static::assertEquals($name, $executor->getName());
    }

    /**
     * Проверка ситуации когда при создании Executor'a указана не корректная конфигурация
     *
     * @expectedException \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     * @expectedExceptionMessage Invalid fixture executor configuration
     *
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Exception
     *
     * @return void
     */
    public function testCreateExecutorWithInvalidCustomConfiguration()
    {
        $configurationClassName = 'invalidClassName';

        try {
            $this->fixtureExecutorManager->get(
                Executor::class,
                [
                    'configuration' => $configurationClassName
                ]
            );
        } catch (ServiceNotCreatedException $e) {
            throw $e->getPrevious();
        }
    }


    /**
     * Проверка возможности указать свой загрузчки фикстур
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     *
     * @return void
     */
    public function testCreateExecutorAndInjectFixtureLoader()
    {
        /** @var FixtureLoaderInterface $fixtureLoader */
        $fixtureLoaderMock = $this->getMock(FixtureLoaderInterface::class);
        $fixtureLoaderName = 'test_fixture_loader';

        /** @var FixtureLoaderManager $fixtureLoaderManager */
        $fixtureLoaderManager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);
        $fixtureLoaderManager->setService($fixtureLoaderName, $fixtureLoaderMock);


        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(
            Executor::class,
            [
                'fixturesLoader' => $fixtureLoaderName
            ]
        );

        static::assertEquals($fixtureLoaderMock, $executor->getLoader());
    }


    /**
     * Проверка возможности указать свой фильтр фикстур
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_Exception
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     *
     * @return void
     */
    public function testCreateExecutorAndInjectFixtureFilter()
    {
        /** @var FixtureFilterInterface $fixtureLoader */
        $fixtureFilterMock = $this->getMock(FixtureFilterInterface::class);
        $fixtureFilterName = 'test_fixture_loader';

        /** @var FixtureFilterManager $fixtureFilterManager */
        $fixtureFilterManager = $this->getApplicationServiceLocator()->get(FixtureFilterManagerInterface::class);
        $fixtureFilterManager->setService($fixtureFilterName, $fixtureFilterMock);


        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(
            Executor::class,
            [
                'filter' => $fixtureFilterName
            ]
        );

        static::assertEquals($fixtureFilterMock, $executor->getFilter());
    }

    /**
     * Проверка создания фильтра по умолчанию
     *
     * @return void
     */
    public function testCreateDefaultFilter()
    {
        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(Executor::class);

        static::assertInstanceOf(ChainFilter::class, $executor->getFilter());
    }

    /**
     * Проверка создания загрузчика фикстур по умолчанию
     *
     * @return void
     */
    public function testCreateDefaultLoader()
    {
        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(Executor::class);

        static::assertInstanceOf(ChainLoader::class, $executor->getLoader());
    }

    /**
     * Проверка получения Executor'a из Doctrine
     *
     * @return void
     */
    public function testGetFixtureExecutor()
    {
        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(Executor::class);

        $originalFixtureExecutor = $executor->getFixtureExecutor();

        static::assertInstanceOf(FixtureExecutor::class, $originalFixtureExecutor);

        $cachedFixtureExecutor = $executor->getFixtureExecutor();

        static::assertEquals($originalFixtureExecutor, $cachedFixtureExecutor);
    }


    /**
     * Запуск импорта данных из фикстур
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testImport()
    {
        /** @var FixtureInterface|PHPUnit_Framework_MockObject_MockObject $fixtureMock */
        $fixtureMock = $this->getMock(FixtureInterface::class);
        $fixtureMock->expects(static::once())->method('import');

        /** @var FixtureLoaderInterface|PHPUnit_Framework_MockObject_MockObject $fixtureLoaderMock */
        $fixtureLoaderMock = $this->getMock(FixtureLoaderInterface::class);
        $fixtureLoaderMock->expects(static::once())->method('load')->will(static::returnValue([$fixtureMock]));

        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(Executor::class);
        $executor->setLoader($fixtureLoaderMock);

        $executor->import();
    }


    /**
     * Запуск удаления данных из фикстур
     *
     * @return void
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testPurge()
    {
        /** @var FixtureInterface|PHPUnit_Framework_MockObject_MockObject $fixtureMock */
        $fixtureMock = $this->getMock(FixtureInterface::class);
        $fixtureMock->expects(static::once())->method('purge');

        /** @var FixtureLoaderInterface|PHPUnit_Framework_MockObject_MockObject $fixtureLoaderMock */
        $fixtureLoaderMock = $this->getMock(FixtureLoaderInterface::class);
        $fixtureLoaderMock->expects(static::once())->method('load')->will(static::returnValue([$fixtureMock]));

        /** @var Executor $executor */
        $executor = $this->fixtureExecutorManager->get(Executor::class);
        $executor->setLoader($fixtureLoaderMock);

        $executor->purge();
    }
}
