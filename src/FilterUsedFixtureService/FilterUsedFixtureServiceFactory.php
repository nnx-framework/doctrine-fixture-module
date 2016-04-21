<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FilterUsedFixtureService;

use Doctrine\Common\Persistence\ManagerRegistry;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FilterUsedFixtureServiceFactory
 *
 * @package Nnx\DoctrineFixtureModule\FilterUsedFixtureService
 */
class FilterUsedFixtureServiceFactory implements FactoryInterface
{
    use EventManagerAwareTrait;

    /**
     * Идендификатор EventManager'a
     *
     * @var array
     */
    protected $eventIdentifier = [
        'DoctrineManagerRegistry'
    ];

    /**
     * @inheritDoc
     *
     * @return FilterUsedFixtureService
     * @throws \Nnx\DoctrineFixtureModule\FilterUsedFixtureService\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //@see \Nnx\Doctrine\Listener\ManagerRegistryListener
        $results = $this->getEventManager()->trigger('get.doctrineManagerRegistry', $this, [], function ($managerRegistry) {
            return $managerRegistry instanceof ManagerRegistry;
        });

        $managerRegistry = $results->last();

        if (!$managerRegistry instanceof ManagerRegistry) {
            $errMsg = 'ManagerRegistry not found';
            throw new Exception\RuntimeException($errMsg);
        }

        return new FilterUsedFixtureService($managerRegistry);
    }
}
