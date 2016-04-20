<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Filter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Fixture\Filter\ChainFilter;

/**
 * Class ChainFilterFactory
 *
 * @package Nnx\DoctrineFixtureModule\Filter
 */
class ChainFilterFactory
    implements FactoryInterface,
    MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ChainFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $filterList = array_key_exists('filterList', $creationOptions) ? $creationOptions['filterList'] : [];

        return new ChainFilter($filterList);
    }
}
