<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

/**
 * Class ObjectManagerNameAwareTrait
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
trait ObjectManagerNameAwareTrait
{
    /**
     * Имя используемого ObjectManager'a
     *
     * @var string
     */
    protected $objectManagerName;

    /**
     * Возвращает имя используемого ObjectManager'a
     *
     * @return string
     */
    public function getObjectManagerName()
    {
        return $this->objectManagerName;
    }

    /**
     * Устанавливает имя используемого ObjectManager'a
     *
     * @param string $objectManagerName
     *
     * @return $this
     */
    public function setObjectManagerName($objectManagerName)
    {
        $this->objectManagerName = $objectManagerName;

        return $this;
    }
}
