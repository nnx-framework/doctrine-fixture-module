<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Loader;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Fixture\Loader\ClassLoader;

/**
 * Class ClassLoaderFactory
 *
 * @package Nnx\DoctrineFixtureModule\Loader
 */
class ClassLoaderFactory
    implements FactoryInterface,
    MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ClassLoader
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $classList = array_key_exists('classList', $creationOptions) ? $creationOptions['classList'] : [];

        return new ClassLoader($classList);
    }
}
