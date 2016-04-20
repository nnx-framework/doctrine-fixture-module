<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Listener;

use Doctrine\Fixture\Persistence\ManagerRegistryEventSubscriber;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;


/**
 * Class ManagerRegistryEventSubscriberFactory
 *
 * @package Nnx\DoctrineFixtureModule\Filter
 */
class ManagerRegistryEventSubscriberFactory
    implements FactoryInterface
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
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ManagerRegistryEventSubscriber
     * @throws \Nnx\DoctrineFixtureModule\Listener\Exception\RuntimeException
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

        return new ManagerRegistryEventSubscriber($managerRegistry);
    }
}
