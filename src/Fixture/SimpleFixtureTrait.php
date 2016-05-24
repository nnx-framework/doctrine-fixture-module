<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture;

use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerInterface;
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
     * Возвращает имя используемого ObjectManager'a
     *
     * @return string
     */
    abstract public function getObjectManagerName();

    /**
     * Возвращает менеджер для работы с данными для фикстуры
     *
     * @return FixtureDataReaderManagerInterface
     */
    abstract public function getFixtureDataReaderManager();

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

        /** @var  $fixtureDataReaderName */
        $fixtureDataReaderName = $this->getFixtureDataReaderName();
        $fixtureDataReader = $this->getFixtureDataReaderManager()->get($fixtureDataReaderName);
        foreach ($filesIterator as $file) {
            /** @var SplFileInfo $file */
            $dataContainer = $fixtureDataReader->loadDataFromResource($file->getRealPath());


//            if (Executor::IMPORT === $method) {
//
//            } elseif (Executor::PURGE === $method) {
//
//            }
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
}
