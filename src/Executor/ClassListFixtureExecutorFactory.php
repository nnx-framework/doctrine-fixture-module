<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManagerInterface;
use Nnx\ModuleOptions\ModuleOptionsPluginManagerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;
use Nnx\DoctrineFixtureModule\Options\ModuleOptions;
use Doctrine\Fixture\Loader\ClassLoader;



/**
 * Class ClassListFixtureExecutorFactory
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
class ClassListFixtureExecutorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() :$serviceLocator;

        $creationOptions = $this->getCreationOptions();

        /** @var DefaultExecutorConfiguration $configuration */
        $configuration = $appServiceLocator->get(DefaultExecutorConfiguration::class);
        /** @var FixtureExecutorBuilderInterface $builder */
        $builder = $appServiceLocator->get(FixtureExecutorBuilderInterface::class);

       /** @var FixtureInitializerManagerInterface $fixtureInitializerManager */
        $fixtureInitializerManager = $appServiceLocator->get(FixtureInitializerManagerInterface::class);

        $executor = new ClassListFixtureExecutor($configuration, $builder, $fixtureInitializerManager);


        /** @var FixtureLoaderManagerInterface $fixtureLoaderManager */
        $fixtureLoaderManager = $appServiceLocator->get(FixtureLoaderManagerInterface::class);

        $classList = array_key_exists('classList', $creationOptions) ? $creationOptions['classList'] : [];

        $classLoaderCreationOptions = [
            'classList' => $classList
        ];
        $loader = $fixtureLoaderManager->get(ClassLoader::class, $classLoaderCreationOptions);
        $executor->setLoader($loader);



        /** @var ModuleOptionsPluginManagerInterface $moduleOptionsPluginManager */
        $moduleOptionsPluginManager = $appServiceLocator->get(ModuleOptionsPluginManagerInterface::class);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $moduleOptionsPluginManager->get(ModuleOptions::class);

        $executor->setContextInitializer($moduleOptions->getContextInitializer());


        return $executor;
    }
}
