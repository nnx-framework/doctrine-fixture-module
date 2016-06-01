<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture;

use Doctrine\Fixture\Fixture;
use Doctrine\Fixture\Persistence\ManagerRegistryFixture;
use Doctrine\Common\Persistence\ManagerRegistry;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureServiceInterface;
use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerAwareInterface;
use Nnx\DoctrineFixtureModule\FixtureInitializer\ObjectManagerNameAwareInterface;


/**
 * Interface SimpleFixtureInterface
 *
 * @package Nnx\DoctrineFixtureModule\Fixture
 */
interface SimpleFixtureInterface
    extends
        Fixture,
        ObjectManagerNameAwareInterface,
        ManagerRegistryFixture,
        FixtureDataReaderManagerAwareInterface
{
    /**
     * Устанавливает сервис SimpleFixture
     *
     * @param SimpleFixtureServiceInterface $simpleFixtureService
     *
     * @return $this
     */
    public function setSimpleFixtureService(SimpleFixtureServiceInterface $simpleFixtureService);

    /**
     * Возвращает сервис SimpleFixture
     *
     * @return SimpleFixtureServiceInterface
     */
    public function getSimpleFixtureService();


    /**
     * Возвращает имя компонента, отвечающего за загрузку данных для фикстуры
     *
     * @return string
     */
    public function getFixtureDataReaderName();


    /**
     * Возвращает имя используемого ObjectManager'a
     *
     * @return string
     */
    public function getObjectManagerName();


    /**
     * Возвращает класс сущности, для которой загружаются данные
     *
     * @return string
     */
    public function getEntityClassName();

    /**
     * Устанавливает класс сущности, для которой загружаются данные
     *
     * @param string $entityClassName
     *
     * @return $this
     */
    public function setEntityClassName($entityClassName);


    /**
     * Возвращает ManagerRegistry
     *
     * @return ManagerRegistry
     */
    public function getManagerRegistry();
}
