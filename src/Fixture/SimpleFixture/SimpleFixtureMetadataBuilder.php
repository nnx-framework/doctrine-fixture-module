<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainerInterface;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Association;

/**
 * Class SimpleFixtureMetadataBuilder
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
class SimpleFixtureMetadataBuilder implements SimpleFixtureMetadataBuilderInterface
{
    /**
     * Контейнер с данными для фикстуры
     *
     * @var DataContainerInterface
     */
    private $dataContainer;

    /**
     * Менеджер доктрины
     *
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var SimpleFixtureMetadataInterface
     */
    private $metadata;

    /**
     * @var SimpleFixtureMetadataInterface
     */
    protected $metadataPrototype;

    /**
     * SimpleFixtureMetadataBuilder constructor.
     *
     * @param SimpleFixtureMetadataInterface $metadataPrototype
     */
    public function __construct(SimpleFixtureMetadataInterface $metadataPrototype)
    {
        $this->metadataPrototype = $metadataPrototype;
    }

    /**
     * Подготавливает метаданные для SimpleFixture
     *
     * @param DataContainerInterface $dataContainer
     * @param                        $entityClassName
     * @param ObjectManager          $objectManager
     *
     * @return SimpleFixtureMetadataInterface
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     */
    public function buildMetadata(DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager)
    {
        $this->dataContainer = $dataContainer;
        $this->objectManager = $objectManager;
        $this->metadata = clone $this->metadataPrototype;

        $entities = $dataContainer->getEntities();

        foreach ($entities as $dataItem) {
            $this->buildMetadataForDataContainer($dataItem, $entityClassName);
        }


        return $this->metadata;
    }

    /**
     * Подготавливает метаданные для сущности
     *
     * @param Entity $dataItem
     * @param        $entityClassName
     *
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     */
    protected function buildMetadataForDataContainer(Entity $dataItem, $entityClassName)
    {
        $this->metadata->linkDataContainerToEntityClassName($dataItem, $entityClassName);

        $dataItemAssociations = $dataItem->getAssociations();

        $entityMetadata = $this->objectManager->getClassMetadata($entityClassName);

        foreach ($dataItemAssociations as $dataItemAssociation) {
            $associationDataItems = $dataItemAssociation->getEntities();
            $associationName = $dataItemAssociation->getName();

            $associationTargetClass = $entityMetadata->getAssociationTargetClass($associationName);

            foreach ($associationDataItems as $associationDataItem) {
                $this->buildReverseAssociationMetadata($associationDataItem, $associationTargetClass, $dataItemAssociation);
                $this->metadata->addAssociationInfo($dataItem, $associationDataItem, $dataItemAssociation);
                $this->buildMetadataForDataContainer($associationDataItem, $associationTargetClass);
            }
        }

        $properties = $dataItem->getProperties();
        if (0 === count($dataItemAssociations) && count($properties) > 0) {
            $this->metadata->addCandidateForSearchInDb($dataItem);
        }
    }

    /**
     * Добавляет метаданные для двухсторонних ассоциаций
     *
     * @param Entity      $childEntity
     * @param             $childEntityClassName
     * @param Association $targetAssociation
     */
    protected function buildReverseAssociationMetadata(Entity $childEntity, $childEntityClassName, Association $targetAssociation)
    {
        $childEntityMetadata = $this->objectManager->getClassMetadata($childEntityClassName);
        $childEntityAssociationNames = $childEntityMetadata->getAssociationNames();

        $targetAssociationName = $targetAssociation->getName();
        foreach ($childEntityAssociationNames as $childEntityAssociationName) {
            if ($childEntityMetadata->isAssociationInverseSide($childEntityAssociationName)) {
                $associationMappedByTargetField = $childEntityMetadata->getAssociationMappedByTargetField($childEntityAssociationName);

                if ($associationMappedByTargetField === $targetAssociationName) {
                    $this->metadata->addReverseAssociationInfo($childEntity, $childEntity->getParentEntity(), $childEntityAssociationName);
                }
            }
        }
    }


    /**
     * Проверка контейнера с данными
     *
     * @param Entity $dataItem
     * @param        $entityClassName
     *
     * @return void
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     */
    protected function validateDataItem(Entity $dataItem, $entityClassName)
    {
        $dataItemAssociations = $dataItem->getAssociations();
        $properties = $dataItem->getProperties();

        $entityMetadata = $this->objectManager->getClassMetadata($entityClassName);
        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (!$entityMetadata->hasField($propertyName)) {
                $errMsg = sprintf('Property %s not found in %s', $propertyName, $entityClassName);
                throw new Exception\RuntimeException($errMsg);
            }
        }

        foreach ($dataItemAssociations as $dataItemAssociation) {
            $dataItemAssociationName = $dataItemAssociation->getName();

            if (!$entityMetadata->hasAssociation($dataItemAssociationName)) {
                $errMsg = sprintf('Association %s not found in %s', $dataItemAssociationName, $entityClassName);
                throw new Exception\RuntimeException($errMsg);
            }
        }

        if (0 === count($dataItemAssociations) && 0 === count($properties)) {
            $errMsg = sprintf('Data container id:%s is empty', $dataItem->getId());
            throw new Exception\RuntimeException($errMsg);
        }
    }
}
