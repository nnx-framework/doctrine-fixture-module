<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;
use ReflectionClass;
use Doctrine\Fixture\Configuration;

/**
 * Class ExecutorFactory
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
class ExecutorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritDoc
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator;
        if ($serviceLocator instanceof AbstractPluginManager) {
            $appServiceLocator = $serviceLocator->getServiceLocator();
        }

        $creationOptions = $this->getCreationOptions();

        $configurationServiceName = DefaultExecutorConfiguration::class;
        if (array_key_exists('configuration', $creationOptions)) {
            $configurationServiceName = $creationOptions['configuration'];
        }

        if ($appServiceLocator->has($configurationServiceName)) {
            /** @var Configuration $configuration */
            $configuration = $appServiceLocator->get($configurationServiceName);
        } elseif (class_exists($configurationServiceName)) {
            $r = new ReflectionClass($configurationServiceName);
            /** @var Configuration $configuration */
            $configuration = $r->newInstance();
        } else {
            $errMsg = 'Invalid fixture executor configuration';
            throw new Exception\RuntimeException($errMsg);
        }

        $builderServiceName = FixtureExecutorBuilderInterface::class;
        if (array_key_exists('builder', $creationOptions)) {
            $builderServiceName = $creationOptions['builder'];
        }
        if ($appServiceLocator->has($builderServiceName)) {
            /** @var FixtureExecutorBuilderInterface $builder */
            $builder = $appServiceLocator->get($builderServiceName);
        } elseif (class_exists($builderServiceName)) {
            $r = new ReflectionClass($builderServiceName);
            /** @var FixtureExecutorBuilderInterface $builder */
            $builder = $r->newInstance();
        } else {
            $errMsg = 'Invalid fixture executor builder';
            throw new Exception\RuntimeException($errMsg);
        }
        $executor = new Executor($configuration, $builder);


        if (array_key_exists('fixturesLoader', $creationOptions)) {
            /** @var FixtureLoaderManagerInterface $fixtureLoaderManager */
            $fixtureLoaderManager = $appServiceLocator->get(FixtureLoaderManagerInterface::class);

            $loaderCreationOptions = [
                'contextExecutor' => $executor
            ];
            $fixtureLoader = $fixtureLoaderManager->get($creationOptions['fixturesLoader'], $loaderCreationOptions);

            $executor->setLoader($fixtureLoader);
        }


        if (array_key_exists('filter', $creationOptions)) {
            /** @var FixtureFilterManagerInterface $fixtureFilterManager */
            $fixtureFilterManager = $appServiceLocator->get(FixtureFilterManagerInterface::class);

            $filterCreationOptions = [
                'contextExecutor' => $executor
            ];
            $fixtureFilter = $fixtureFilterManager->get($creationOptions['filter'], $filterCreationOptions);

            $executor->setFilter($fixtureFilter);
        }

        if (array_key_exists('name', $creationOptions)) {
            $executor->setName($creationOptions['name']);
        }

        return $executor;
    }
}
