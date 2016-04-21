<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Utils;

use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Interface ManagerRegistryProviderInterface
 *
 * @package Nnx\DoctrineFixtureModule\Utils
 */
interface ManagerRegistryProviderInterface
{

    /**
     * Возвращает компонент для работы с ObjectManager's
     *
     * @return ManagerRegistry
     *
     */
    public function getManagerRegistry();
}
