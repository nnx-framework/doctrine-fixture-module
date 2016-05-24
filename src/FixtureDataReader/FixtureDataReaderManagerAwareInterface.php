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
interface FixtureDataReaderManagerAwareInterface
{
    /**
     * Устанавливает менеджер для работы с данными для фикстуры
     *
     * @param FixtureDataReaderManagerInterface $fixtureDataReaderManager
     *
     * @return $this
     */
    public function setFixtureDataReaderManager(FixtureDataReaderManagerInterface $fixtureDataReaderManager);
}
