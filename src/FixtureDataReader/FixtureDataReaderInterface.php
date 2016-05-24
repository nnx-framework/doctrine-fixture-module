<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

/**
 * Interface FixtureDataReaderInterface
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader
 */
interface FixtureDataReaderInterface
{
    /**
     * Загружает данные для фикстуры, на основе заданного ресурса
     *
     * @param $resource
     *
     * @return DataContainerInterface
     */
    public function loadDataFromResource($resource);
}
