<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Doctrine\Common\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainerInterface;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;
use Doctrine\Common\Util\Inflector;
use Doctrine\Common\Collections\Collection;
use DateTime;

/**
 * Class SimpleFixtureImportEngine
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
class SimpleFixtureImportEngine implements SimpleFixtureImportEngineInterface
{

    /**
     * Контейнер с данными
     *
     * @var DataContainerInterface
     */
    private $dataContainer;

    /**
     * Метаданные необходимые для заполнения бд
     *
     * @var SimpleFixtureMetadataInterface
     */
    private $metadata;

    /**
     * Менеджер объектов доктрины
     *
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * Связывает id контейнера с данными, с сущностью
     *
     * @var array
     */
    private $dataContainerIdToDoctrineEntity = [];

    /**
     * Компонет отвечающий за создание сущностей
     *
     * @var ContainerInterface
     */
    protected $entityLocator;

    /**
     * SimpleFixtureImportEngine constructor.
     *
     * @param ContainerInterface $entityContainer
     */
    public function __construct(ContainerInterface $entityContainer)
    {
        $this->setEntityLocator($entityContainer);
    }

    /**
     * Запуск процесса импорта данных
     *
     * @param DataContainerInterface         $dataContainer
     * @param SimpleFixtureMetadataInterface $metadata
     * @param ObjectManager                  $objectManager
     *
     *
     * @return void
     * 
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     * @throws \UnexpectedValueException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\RuntimeException
     */
    public function run(DataContainerInterface $dataContainer, SimpleFixtureMetadataInterface $metadata, ObjectManager $objectManager)
    {
        $this->dataContainer = $dataContainer;
        $this->metadata = $metadata;
        $this->objectManager = $objectManager;

        $this->initEntities();
        $this->hydrateAssociation();
        $this->hydrateProperties();
    }

    /**
     * Заполняет сущности данными
     *
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     */
    protected function hydrateProperties()
    {
        $index = $this->dataContainer->getIndex();
        $dataItems = $index->getEntities();
        foreach ($dataItems as $dataItem) {
            $properties = $dataItem->getProperties();

            $entity = $this->getDoctrineEntityByDataContainer($dataItem);
            $metadata = $this->objectManager->getClassMetadata(get_class($entity));
            foreach ($properties as $property) {
                $propertyName = $property->getName();
                $setter = 'set' . Inflector::classify($propertyName);

                $typeField = $metadata->getTypeOfField($propertyName);

                $value = $this->handleTypeConversions($property->getValue(), $typeField);

                $metadata->getReflectionClass()->getMethod($setter)->invoke($entity, $value);
            }
        }
    }

    /**
     * Преобразование типов
     *
     * @param  mixed  $value
     * @param  string $typeOfField
     *
     * @return mixed
     */
    protected function handleTypeConversions($value, $typeOfField)
    {
        switch ($typeOfField) {
            case 'datetimetz':
            case 'datetime':
            case 'time':
            case 'date':
                if (is_int($value)) {
                    $dateTime = new DateTime();
                    $dateTime->setTimestamp($value);
                    $value = $dateTime;
                } elseif (is_string($value)) {
                    $value = '' === $value ? new DateTime() : new DateTime($value);
                }
                break;
            default:
        }

        return $value;
    }


    /**
     * Проставляет связи между сущностями
     *
     * @return void
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\RuntimeException
     */
    protected function hydrateAssociation()
    {
        $index = $this->dataContainer->getIndex();
        $dataItems = $index->getEntities();
        foreach ($dataItems as $dataItem) {
            if (!$this->metadata->hasAssociationsForEntity($dataItem)) {
                continue;
            }

            $associations = $this->metadata->getAssociationsForEntity($dataItem);
            $entity = $this->getDoctrineEntityByDataContainer($dataItem);

            $entityClassName = get_class($entity);
            $entityMetadata = $this->objectManager->getClassMetadata($entityClassName);

            $rClass = $entityMetadata->getReflectionClass();
            foreach ($associations as $associationName => $associationDataContainers) {
                if ($entityMetadata->isCollectionValuedAssociation($associationName)) {
                    $getter = 'get' . Inflector::classify($associationName);
                    $collection = $rClass->getMethod($getter)->invoke($entity);

                    if (!$collection instanceof Collection) {
                        $errMsg = sprintf('Property %s in entity %s not collection', $associationName, $entityClassName);
                        throw new Exception\RuntimeException($errMsg);
                    }

                    foreach ($associationDataContainers as $associationDataContainerId) {
                        $associationDataContainer = $index->getEntityById($associationDataContainerId);
                        $associationEntity = $this->getDoctrineEntityByDataContainer($associationDataContainer);
                        $collection->add($associationEntity);
                    }
                } elseif ($entityMetadata->isSingleValuedAssociation($associationName)) {
                    $setter = 'set' . Inflector::classify($associationName);

                    $associationDataContainerId = current($associationDataContainers);
                    $associationDataContainer = $index->getEntityById($associationDataContainerId);
                    $associationEntity = $this->getDoctrineEntityByDataContainer($associationDataContainer);

                    $rClass->getMethod($setter)->invoke($entity, $associationEntity);
                }
            }
        }
    }

    /**
     * Инциализация сущностей
     *
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     * @throws \UnexpectedValueException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    protected function initEntities()
    {
        $dataItems = $this->dataContainer->getIndex()->getEntities();
        foreach ($dataItems as $dataItem) {
            $this->initEntity($dataItem);
        }
    }


    /**
     * Инциализация сущности
     *
     * @param Entity $dataItem
     *
     * @throws \UnexpectedValueException
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    protected function initEntity(Entity $dataItem)
    {
        if ($this->metadata->isCandidateForSearchInDb($dataItem)) {
            $this->initEntityFromPersistenceStorage($dataItem);
        } else {
            $this->createEntity($dataItem);
        }
    }

    /**
     * Ищет сущность в хранилище
     *
     * @param Entity $dataItem
     *
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     * @throws \UnexpectedValueException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    protected function initEntityFromPersistenceStorage(Entity $dataItem)
    {
        $entityClassName = $this->metadata->getEntityClassNameByDataContainer($dataItem);
        $entityRepository = $this->objectManager->getRepository($entityClassName);
        $searchCriteria = $this->buildSearchCriteria($dataItem);

        $findEntities = $entityRepository->findBy($searchCriteria);

        $countFindEntities = count($findEntities);
        if ($countFindEntities > 1) {
            $errMsg = 'Found more than one entity';
            throw new Exception\RuntimeException($errMsg);
        }

        if (1 === $countFindEntities) {
            $this->dataContainerIdToDoctrineEntity[$dataItem->getId()] = array_pop($findEntities);
        } else {
            $this->createEntity($dataItem);
        }
    }

    /**
     * @param Entity $dataItem
     *
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Interop\Container\Exception\NotFoundException
     */
    protected function createEntity(Entity $dataItem)
    {
        $entityClassName = $this->metadata->getEntityClassNameByDataContainer($dataItem);

        $entity = $this->getEntityLocator()->get($entityClassName);

        $this->objectManager->persist($entity);

        $this->dataContainerIdToDoctrineEntity[$dataItem->getId()] = $entity;
    }

    /**
     * Получает сущность, которая соответствует контейнеру с данными
     *
     * @param Entity $dataItem
     *
     * @return mixed
     * @throws \Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\Exception\RuntimeException
     */
    protected function getDoctrineEntityByDataContainer(Entity $dataItem)
    {
        $dataItemId = $dataItem->getId();
        if (!array_key_exists($dataItemId, $this->dataContainerIdToDoctrineEntity)) {
            $errMsg = sprintf('Doctrine entity not found for data container: id# %s', $dataItemId);
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->dataContainerIdToDoctrineEntity[$dataItemId];
    }

    /**
     * Подготовка критериев для поиска в базе данных
     *
     * @param Entity $dataItem
     *
     * @return array
     */
    protected function buildSearchCriteria(Entity $dataItem)
    {
        $searchCriteria = [];

        foreach ($dataItem->getProperties() as $property) {
            $searchCriteria[$property->getName()] = $property->getValue();
        }

        return $searchCriteria;
    }

    /**
     * Устанавливает компонент отвечаюзий за создание сущностей
     *
     * @return ContainerInterface
     */
    public function getEntityLocator()
    {
        return $this->entityLocator;
    }

    /**
     * Возвращает компонент отвечаюзий за создание сущностей
     *
     * @param ContainerInterface $entityLocator
     *
     * @return $this
     */
    public function setEntityLocator(ContainerInterface $entityLocator)
    {
        $this->entityLocator = $entityLocator;

        return $this;
    }
}
