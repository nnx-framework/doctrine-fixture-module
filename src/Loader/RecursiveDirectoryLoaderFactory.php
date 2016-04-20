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
use Doctrine\Fixture\Loader\RecursiveDirectoryLoader;

/**
 * Class RecursiveDirectoryLoaderFactory
 *
 * @package Nnx\DoctrineFixtureModule\Loader
 */
class RecursiveDirectoryLoaderFactory
    implements FactoryInterface,
    MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RecursiveDirectoryLoader
     * @throws \InvalidArgumentException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $directory = array_key_exists('directory', $creationOptions) ? $creationOptions['directory'] : null;

        return new RecursiveDirectoryLoader($directory);
    }
}
