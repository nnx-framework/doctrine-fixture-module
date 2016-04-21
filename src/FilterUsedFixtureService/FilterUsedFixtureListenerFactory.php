<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FilterUsedFixtureService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FilterUsedFixtureListenerFactory
 *
 * @package Nnx\DoctrineFixtureModule\FilterUsedFixtureService
 */
class FilterUsedFixtureListenerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FilterUsedFixtureServiceInterface $filterUsedFixtureService */
        $filterUsedFixtureService = $serviceLocator->get(FilterUsedFixtureServiceInterface::class);

        return new FilterUsedFixtureListener($filterUsedFixtureService);
    }
}
