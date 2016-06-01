<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainerInterface;


/**
 * Interface SimpleFixtureImportEngineInterface
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
interface SimpleFixtureImportEngineInterface
{
    /**
     * Запуск процесса импорта данных
     *
     * @param DataContainerInterface         $dataContainer
     * @param SimpleFixtureMetadataInterface $metadata
     * @param ObjectManager                  $objectManager
     */
    public function run(DataContainerInterface $dataContainer, SimpleFixtureMetadataInterface $metadata, ObjectManager $objectManager);
}
