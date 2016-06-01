<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture;

use Doctrine\Common\Persistence\ManagerRegistry;
use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerInterface;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureServiceInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Doctrine\Fixture\Executor;
use Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat as SimpleXmlFormatFixtureDataReader;

/**
 * Class SimpleFixtureTrait
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
trait SimpleFixtureTrait
{
    /**
     * Путь до директории с ресурсами
     *
     * @var string
     */
    protected $resourceFixtureDir;

    /**
     * Компонет для поиска файлов с ресурсами
     *
     * @var Finder
     */
    protected $resourceFilesFinder;

    /**
     * Паттерн описывающий какие файлы содрежать данные для фикстур
     *
     * @var string
     */
    protected $resourceFixtureFileNamePattern = '*.xml';

    /**
     * Имя компонента, отвечающего за загрузку данных для фикстуры
     *
     * @var string
     */
    protected $fixtureDataReaderName = SimpleXmlFormatFixtureDataReader::class;

    /**
     * Класс сущности, для которой загружаются данные
     *
     * @var string
     */
    protected $entityClassName;


    /**
     * @return SimpleFixtureServiceInterface
     */
    abstract public function getSimpleFixtureService();

    /**
     * Возвращает ManagerRegistry
     *
     * @return ManagerRegistry
     */
    abstract public function getManagerRegistry();

    /**
     * Возвращает менеджер для работы с данными для фикстуры
     *
     * @return FixtureDataReaderManagerInterface
     */
    abstract public function getFixtureDataReaderManager();

    /**
     * Возвращает имя компонента, отвечающего за загрузку данных для фикстуры
     *
     * @return string
     */
    public function getFixtureDataReaderName()
    {
        return $this->fixtureDataReaderName;
    }

    /**
     * Устанавливает имя компонента, отвечающего за загрузку данных для фикстуры
     *
     * @param string $fixtureDataReaderName
     *
     * @return $this
     */
    public function setFixtureDataReaderName($fixtureDataReaderName)
    {
        $this->fixtureDataReaderName = (string)$fixtureDataReaderName;

        return $this;
    }


    /**
     * Возвращает компонет для поиска файлов с ресурсами
     *
     * @return Finder
     */
    public function getResourceFilesFinder()
    {
        if (null === $this->resourceFilesFinder) {
            $filesFinder = new Finder();
            $this->setResourceFilesFinder($filesFinder);
        }
        return $this->resourceFilesFinder;
    }

    /**
     * Устанавливает компонет для поиска файлов с ресурсами
     *
     * @param Finder $resourceFilesFinder
     *
     * @return $this
     */
    public function setResourceFilesFinder(Finder $resourceFilesFinder)
    {
        $this->resourceFilesFinder = $resourceFilesFinder;

        return $this;
    }

    /**
     * Настройка компонета, отвечающего за поиск файлов ресурсов
     *
     *
     * @param Finder $resourceFilesFinder
     *
     * @return void
     * @throws \Nnx\DoctrineFixtureModule\Fixture\Exception\RuntimeException
     * @throws \InvalidArgumentException
     */
    public function configureResourceFilesFinder(Finder $resourceFilesFinder)
    {
        $resourceFilesFinder
            ->files()
            ->in([$this->getResourceFixtureDir()])
            ->followLinks()
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->name($this->getResourceFixtureFileNamePattern());
    }

    /**
     * Возвращает директорию в которой находятся ресурсы для фикстуры
     *
     * @return string
     *
     * @throws \Nnx\DoctrineFixtureModule\Fixture\Exception\RuntimeException
     */
    public function getResourceFixtureDir()
    {
        $correctDir = is_string($this->resourceFixtureDir)
            && file_exists($this->resourceFixtureDir)
            && is_dir($this->resourceFixtureDir)
            && is_readable($this->resourceFixtureDir);
        if (!$correctDir) {
            $errMsg = sprintf(
                'Invalid resource fixture directory: %s',
                is_string($this->resourceFixtureDir) ? $this->resourceFixtureDir : ''
            );
            throw new Exception\RuntimeException($errMsg);
        }

        return $this->resourceFixtureDir;
    }


    /**
     * Устанавливает директорию в которой находятся ресурсы для фикстуры
     *
     * @param string $resourceFixtureDir
     *
     * @return $this
     */
    public function setResourceFixtureDir($resourceFixtureDir)
    {
        $this->resourceFixtureDir = $resourceFixtureDir;

        return $this;
    }

    /**
     * Возвращает паттерн описывающий какие файлы содрежат данные для фикстур
     *
     * @return string
     */
    public function getResourceFixtureFileNamePattern()
    {
        return $this->resourceFixtureFileNamePattern;
    }

    /**
     * Устанавливает паттерн описывающий какие файлы содрежат данные для фикстур
     *
     * @param string $resourceFixtureFileNamePattern
     *
     * @return $this
     */
    public function setResourceFixtureFileNamePattern($resourceFixtureFileNamePattern)
    {
        $this->resourceFixtureFileNamePattern = $resourceFixtureFileNamePattern;

        return $this;
    }


    /**
     * @inheritdoc
     *
     * @throws \InvalidArgumentException
     * @throws \Nnx\DoctrineFixtureModule\Fixture\Exception\RuntimeException
     */
    public function import()
    {
        $this->executeFixture(Executor::IMPORT);
    }

    protected function executeFixture($method)
    {
        $filesIterator = $this->getResourceFilesFinder();
        $this->configureResourceFilesFinder($filesIterator);


        $simpleFixtureService = $this->getSimpleFixtureService();

        $objectManagerName = $this->getObjectManagerName();
        $managerRegistry = $this->getManagerRegistry();
        $objectManager = $managerRegistry->getManager($objectManagerName);
        $entityClassName = $this->getEntityClassName();
        /** @var  $fixtureDataReaderName */
        $fixtureDataReaderName = $this->getFixtureDataReaderName();


        $fixtureDataReaderManager = $this->getFixtureDataReaderManager();
        $fixtureDataReader = $fixtureDataReaderManager->get($fixtureDataReaderName);

        foreach ($filesIterator as $file) {
            /** @var SplFileInfo $file */
            $path = $file->getRealPath();

            $dataContainer = $fixtureDataReader->loadDataFromResource($path);

            if (Executor::IMPORT === $method) {
                $simpleFixtureService->import($dataContainer, $entityClassName, $objectManager);
            } elseif (Executor::PURGE === $method) {
                $simpleFixtureService->purge($dataContainer, $entityClassName, $objectManager);
            }
        }
    }


    /**
     * @inheritdoc
     *
     */
    public function purge()
    {
        $this->executeFixture(Executor::PURGE);
    }

    /**
     * Возвращает класс сущности, для которой загружаются данные
     *
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * Устанавливает класс сущности, для которой загружаются данные
     *
     * @param string $entityClassName
     *
     * @return $this
     */
    public function setEntityClassName($entityClassName)
    {
        $this->entityClassName = $entityClassName;

        return $this;
    }
}
