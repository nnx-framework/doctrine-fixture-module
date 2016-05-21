<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\Test\ResourceLoader;

use Nnx\DoctrineFixtureModule\PhpUnit\TestData\TestPaths;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderManager;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderManagerInterface;
use Nnx\ZF2TestToolkit\Utils\OverrideAppConfigTrait;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class ResourceLoaderManagerTest
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\Test\ResourceLoader
 */
class ResourceLoaderManagerTest extends AbstractHttpControllerTestCase
{
    use OverrideAppConfigTrait;

    /**
     * Менеджер плагинов, отвечающиз за загрузку ресурсов для фикстур
     *
     * @var ResourceLoaderManager
     */
    protected $resourceLoaderManager;

    /**
     * Конфиги для перегрузки, конфигов приложения
     *
     * @var array
     */
    protected $overrideAppConfig = [
        ResourceLoaderManager::CONFIG_KEY => [
            'invokables' => [
                'testNoValidPlugin' => \stdClass::class
            ]
        ]
    ];

    /**
     * @inheritDoc
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     */
    protected function setUp()
    {
        parent::setUp();

        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToFixtureTestAppConfig()
        );
        $this->overrideAppConfig($this->overrideAppConfig);

        $this->resourceLoaderManager = $this->getApplicationServiceLocator()->get(ResourceLoaderManagerInterface::class);
    }

    /**
     * Проверка ситуации когда, происходит попытка получить не валидный плагин
     *
     * @expectedException \Nnx\DoctrineFixtureModule\ResourceLoader\Exception\RuntimeException
     * @expectedExceptionMessage Plugin of type stdClass is invalid; must implement Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderInterface
     *
     *
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNotValidatePlugin()
    {
        $this->resourceLoaderManager->get('testNoValidPlugin');
    }
}
