<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Loader;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;
use Nnx\DoctrineFixtureModule\Module;
use Doctrine\Fixture\Loader\ChainLoader;


/**
 * Class FixtureLoaderAbstractFactory
 *
 * @package Nnx\DoctrineFixtureModule\Loader
 */
class FixtureLoaderAbstractFactory
    implements AbstractFactoryInterface
{

    /**
     * Флаг определеят была ли инициализирована фабрика
     *
     * @var bool
     */
    protected $isInit = false;

    /**
     * Конфиг с настройками загрузчиков фикстур
     *
     * @var array
     */
    protected $fixtureLoaderConfig = [];

    /**
     * Инициализация фабрики
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    protected function initFactory(ServiceLocatorInterface $serviceLocator)
    {
        if (true === $this->isInit) {
            return;
        }

        $appServiceLocator = $serviceLocator;
        if ($serviceLocator instanceof AbstractPluginManager) {
            $appServiceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        $this->fixtureLoaderConfig = $moduleOptions->getFixturesLoaders();


        $this->isInit = true;
    }


    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $this->initFactory($serviceLocator);

        return array_key_exists($requestedName, $this->fixtureLoaderConfig);
    }

    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Loader\Exception\RuntimeException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $this->initFactory($serviceLocator);

        /** @var FixtureLoaderManagerInterface  $serviceLocator*/


        $loadersConfigs = $this->fixtureLoaderConfig[$requestedName];

        $chains = [];
        foreach ($loadersConfigs as $index => $item) {
            if (!is_array($item)) {
                $errMsg = sprintf(
                    'Item [%s][fixtures][%s] of type %s is invalid. Must array',
                    Module::CONFIG_KEY,
                    $index,
                    (is_object($item) ? get_class($item) : gettype($item))
                );
                throw new Exception\RuntimeException($errMsg);
            }

            if (!array_key_exists('name', $item)) {
                $errMsg = sprintf(
                    'Required parameter [%s][fixtures][%s][\'name\'] not found',
                    Module::CONFIG_KEY,
                    $index
                );
                throw new Exception\RuntimeException($errMsg);
            }

            if (!is_string($item['name'])) {
                $errMsg = sprintf(
                    'Parameter [%s][fixtures][%s][\'name\'] of type %s is invalid. Must string',
                    Module::CONFIG_KEY,
                    $index,
                    (is_object($item['name']) ? get_class($item['name']) : gettype($item['name']))
                );
                throw new Exception\RuntimeException($errMsg);
            }

            $name = $item['name'];
            $options = array_key_exists('options', $item) ? $item['options'] : [];
            if (!is_array($options)) {
                $errMsg = sprintf(
                    'Parameter [%s][fixtures][%s][\'options\'] of type %s is invalid. Must array',
                    Module::CONFIG_KEY,
                    $index,
                    (is_object($options) ? get_class($options) : gettype($options))
                );
                throw new Exception\RuntimeException($errMsg);
            }

            $chains[] = $serviceLocator->get($name, $options);
        }

        return $serviceLocator->get(ChainLoader::class, [
            'loaderList' => $chains
        ]);
    }
}
