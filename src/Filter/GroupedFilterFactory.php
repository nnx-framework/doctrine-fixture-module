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
use Doctrine\Fixture\Filter\GroupedFilter;

/**
 * Class GroupedFilterFactory
 *
 * @package Nnx\DoctrineFixtureModule\Filter
 */
class GroupedFilterFactory
    implements FactoryInterface,
    MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritdoc
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return GroupedFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();

        $allowedGroupList = array_key_exists('allowedGroupList', $creationOptions) ? $creationOptions['allowedGroupList'] : [];
        $onlyImplementors = array_key_exists('onlyImplementors', $creationOptions) ? (bool)$creationOptions['onlyImplementors'] : false;

        return new GroupedFilter($allowedGroupList, $onlyImplementors);
    }
}
