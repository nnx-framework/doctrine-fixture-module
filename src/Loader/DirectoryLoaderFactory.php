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
use Doctrine\Fixture\Loader\DirectoryLoader;

/**
 * Class DirectoryLoaderFactory
 *
 * @package Nnx\DoctrineFixtureModule\Loader
 */
class DirectoryLoaderFactory
    implements FactoryInterface,
    MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return DirectoryLoader
     * @throws \InvalidArgumentException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $directory = array_key_exists('directory', $creationOptions) ? $creationOptions['directory'] : null;

        return new DirectoryLoader($directory);
    }
}
