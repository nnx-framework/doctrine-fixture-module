<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

/**
 * Interface ObjectManagerNameAwareInterface
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
interface ObjectManagerNameAwareInterface
{
    /**
     * Устанавливает имя используемого ObjectManager'a
     *
     * @param $objectManagerName
     *
     * @return $this
     */
    public function setObjectManagerName($objectManagerName);
}
