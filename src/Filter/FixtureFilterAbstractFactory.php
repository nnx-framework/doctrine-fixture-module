<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Filter;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;
use Nnx\DoctrineFixtureModule\Module;
use Doctrine\Fixture\Filter\ChainFilter;


/**
 * Class FixtureFilterAbstractFactory
 *
 * @package Nnx\DoctrineFixtureModule\Filter
 */
class FixtureFilterAbstractFactory
    implements AbstractFactoryInterface, MutableCreationOptionsInterface
{

    use MutableCreationOptionsTrait;

    /**
     * Флаг определеят была ли инициализирована фабрика
     *
     * @var bool
     */
    protected $isInit = false;

    /**
     * Конфиг с настройками фильтров фикстур
     *
     * @var array
     */
    protected $fixtureFilterConfig = [];

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

        $this->fixtureFilterConfig = $moduleOptions->getFilters();


        $this->isInit = true;
    }


    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $this->initFactory($serviceLocator);

        return array_key_exists($requestedName, $this->fixtureFilterConfig);
    }

    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Filter\Exception\RuntimeException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $this->initFactory($serviceLocator);

        /** @var FixtureFilterManagerInterface  $serviceLocator*/


        $filtersConfigs = $this->fixtureFilterConfig[$requestedName];

        $creationOptions = $this->getCreationOptions();
        $contextExecutor = array_key_exists('contextExecutor', $creationOptions) ? $creationOptions['contextExecutor'] : null;

        $chains = [];
        foreach ($filtersConfigs as $index => $item) {
            if (!is_array($item)) {
                $errMsg = sprintf(
                    'Item [%s][filters][%s] of type %s is invalid. Must array',
                    Module::CONFIG_KEY,
                    $index,
                    (is_object($item) ? get_class($item) : gettype($item))
                );
                throw new Exception\RuntimeException($errMsg);
            }

            if (!array_key_exists('name', $item)) {
                $errMsg = sprintf(
                    'Required parameter [%s][filters][%s][\'name\'] not found',
                    Module::CONFIG_KEY,
                    $index
                );
                throw new Exception\RuntimeException($errMsg);
            }

            if (!is_string($item['name'])) {
                $errMsg = sprintf(
                    'Parameter [%s][filters][%s][\'name\'] of type %s is invalid. Must string',
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
                    'Parameter [%s][filters][%s][\'options\'] of type %s is invalid. Must array',
                    Module::CONFIG_KEY,
                    $index,
                    (is_object($options) ? get_class($options) : gettype($options))
                );
                throw new Exception\RuntimeException($errMsg);
            }
            if (null !== $contextExecutor) {
                $options['contextExecutor'] = $contextExecutor;
            }

            $chains[] = $serviceLocator->get($name, $options);
        }

        $chainFilterOptions = [
            'filterList' => $chains
        ];
        if (null !== $contextExecutor) {
            $chainFilterOptions['contextExecutor'] = $contextExecutor;
        }

        return $serviceLocator->get(ChainFilter::class, $chainFilterOptions);
    }
}
