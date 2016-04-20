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
use Doctrine\Fixture\Loader\ChainLoader;

/**
 * Class ChainLoaderFactory
 *
 * @package Nnx\DoctrineFixtureModule\Loader
 */
class ChainLoaderFactory
    implements FactoryInterface,
    MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ChainLoader
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $loaderList = array_key_exists('loaderList', $creationOptions) ? $creationOptions['loaderList'] : [];

        return new ChainLoader($loaderList);
    }
}
