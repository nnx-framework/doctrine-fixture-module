<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader;

use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerInterface;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Nnx\ZF2TestToolkit\Utils\OverrideAppConfigTrait;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class FixtureDataReaderManagerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\FixtureDataReader
 */
class FixtureDataReaderManagerTest extends AbstractHttpControllerTestCase
{
    use OverrideAppConfigTrait;

    /**
     * Установка окружения
     *
     * @expectedException \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\RuntimeException
     * @expectedExceptionMessage lugin of type stdClass is invalid; must implement Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderInterface
     *
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testInvalidPlugin()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );

        /** @var AbstractPluginManager $fixtureDataReaderManager */
        $fixtureDataReaderManager = $this->getApplicationServiceLocator()->get(FixtureDataReaderManagerInterface::class);

        $fixtureDataReaderManager->setInvokableClass('test', \stdClass::class);
        $fixtureDataReaderManager->get('test');
    }
}
