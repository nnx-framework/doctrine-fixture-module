<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Utils;

use Zend\EventManager\EventManagerAwareTrait;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ManagerRegistryProvider
 *
 * @package Nnx\DoctrineFixtureModule\Utils
 */
class ManagerRegistryProvider implements ManagerRegistryProviderInterface
{
    use EventManagerAwareTrait;

    /**
     * Компонент для работы с ObjectManager's
     *
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * Идендификатор EventManager'a
     *
     * @var array
     */
    protected $eventIdentifier = [
        'DoctrineManagerRegistry'
    ];

    /**
     * Возвращает компонент для работы с ObjectManager's
     *
     * @return ManagerRegistry
     *
     * @throws Exception\RuntimeException
     */
    public function getManagerRegistry()
    {
        if (null !== $this->managerRegistry) {
            return $this->managerRegistry;
        }

        //@see \Nnx\Doctrine\Listener\ManagerRegistryListener
        $results = $this->getEventManager()->trigger('get.doctrineManagerRegistry', $this, [], function ($managerRegistry) {
            return $managerRegistry instanceof ManagerRegistry;
        });

        $managerRegistry = $results->last();

        if (!$managerRegistry instanceof ManagerRegistry) {
            $errMsg = 'ManagerRegistry not found';
            throw new Exception\RuntimeException($errMsg);
        }

        $this->managerRegistry = $managerRegistry;
        return $managerRegistry;
    }

    /**
     * Устанавливает компонент для работы с ObjectManager's
     *
     * @param ManagerRegistry $managerRegistry
     *
     * @return $this
     */
    public function setManagerRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;

        return $this;
    }
}
