<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

/**
 * Interface FixtureDataReaderManagerAwareInterface
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader
 */
trait FixtureDataReaderManagerAwareTrait
{
    /**
     * Менеджер для работы с данными для фикстуры
     *
     * @var FixtureDataReaderManagerInterface
     */
    protected $fixtureDataReaderManager;

    /**
     * Устанавливает менеджер для работы с данными для фикстуры
     *
     * @param FixtureDataReaderManagerInterface $fixtureDataReaderManager
     *
     * @return $this
     */
    public function setFixtureDataReaderManager(FixtureDataReaderManagerInterface $fixtureDataReaderManager)
    {
        $this->fixtureDataReaderManager = $fixtureDataReaderManager;

        return $this;
    }

    /**
     * Возвращает менеджер для работы с данными для фикстуры
     *
     * @return FixtureDataReaderManagerInterface
     */
    public function getFixtureDataReaderManager()
    {
        return $this->fixtureDataReaderManager;
    }
}
