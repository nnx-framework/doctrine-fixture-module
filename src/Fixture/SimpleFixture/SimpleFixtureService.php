<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainerInterface;

/**
 * Class SimpleFixtureService
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
class SimpleFixtureService implements SimpleFixtureServiceInterface
{

    /**
     * Компонент отвечающий за сборку метаданных
     *
     * @var SimpleFixtureMetadataBuilderInterface
     */
    protected $simpleFixtureMetadataBuilder;

    /**
     * Движок отвечающий за заполнение базы данных
     *
     * @var SimpleFixtureImportEngineInterface
     */
    protected $simpleFixtureImportEngine;

    /**
     * SimpleFixtureService constructor.
     *
     * @param SimpleFixtureMetadataBuilderInterface $simpleFixtureMetadataBuilder
     * @param SimpleFixtureImportEngineInterface    $simpleFixtureImportEngine
     */
    public function __construct(
        SimpleFixtureMetadataBuilderInterface $simpleFixtureMetadataBuilder,
        SimpleFixtureImportEngineInterface $simpleFixtureImportEngine
    ) {
        $this->setSimpleFixtureMetadataBuilder($simpleFixtureMetadataBuilder);
        $this->setSimpleFixtureImportEngine($simpleFixtureImportEngine);
    }

    /**
     * Возвращает компонент отвечающий за сборку метаданных
     *
     * @return SimpleFixtureMetadataBuilderInterface
     */
    public function getSimpleFixtureMetadataBuilder()
    {
        return $this->simpleFixtureMetadataBuilder;
    }

    /**
     * Возвращает движок, отвечающий за заполнение базы данных
     *
     * @return SimpleFixtureImportEngineInterface
     */
    public function getSimpleFixtureImportEngine()
    {
        return $this->simpleFixtureImportEngine;
    }

    /**
     * Устанавливает движок отвечающий за заполнение базы данных
     *
     * @param SimpleFixtureImportEngineInterface $simpleFixtureImportEngine
     *
     * @return $this
     */
    public function setSimpleFixtureImportEngine(SimpleFixtureImportEngineInterface $simpleFixtureImportEngine)
    {
        $this->simpleFixtureImportEngine = $simpleFixtureImportEngine;

        return $this;
    }


    /**
     * Устанавливает компонент отвечающий за сборку метаданных
     *
     * @param SimpleFixtureMetadataBuilderInterface $simpleFixtureMetadataBuilder
     *
     * @return $this
     */
    public function setSimpleFixtureMetadataBuilder(SimpleFixtureMetadataBuilderInterface $simpleFixtureMetadataBuilder)
    {
        $this->simpleFixtureMetadataBuilder = $simpleFixtureMetadataBuilder;

        return $this;
    }


    /**
     * Загрузка данных
     *
     * @param DataContainerInterface $dataContainer
     * @param string $entityClassName
     * @param ObjectManager $objectManager
     *
     * @return $this
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\InvalidContextException
     */
    public function import(DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager)
    {
        $metadata = $this->getSimpleFixtureMetadataBuilder()->buildMetadata($dataContainer, $entityClassName, $objectManager);

        $this->getSimpleFixtureImportEngine()->run($dataContainer, $metadata, $objectManager);
        $objectManager->flush();
        return $this;
    }

    /**
     * Удаление данных
     *
     * @param DataContainerInterface $dataContainer
     * @param string $entityClassName
     * @param ObjectManager $objectManager
     *
     * @return $this
     */
    public function purge(DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager)
    {
    }
}
