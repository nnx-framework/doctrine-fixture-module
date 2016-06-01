<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainerInterface;

/**
 * Interface SimpleFixtureServiceInterface
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
interface SimpleFixtureServiceInterface
{
    /**
     * Загрузка данных
     *
     * @param DataContainerInterface $dataContainer
     * @param string $entityClassName
     * @param ObjectManager $objectManager
     *
     * @return $this
     */
    public function import(DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager);

    /**
     * Удаление данных
     *
     * @param DataContainerInterface $dataContainer
     * @param string $entityClassName
     * @param ObjectManager $objectManager
     *
     * @return $this
     */
    public function purge(DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager);
}
