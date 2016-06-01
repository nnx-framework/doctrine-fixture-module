<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ManagerRegistryAwareTrait
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
trait ManagerRegistryAwareTrait
{
    /**
     * Менеджер хранилищ доктрины
     *
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * Возвращает ManagerRegistry
     *
     * @return ManagerRegistry
     */
    public function getManagerRegistry()
    {
        return $this->managerRegistry;
    }

    /**
     * Устанавливает ManagerRegistry
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
