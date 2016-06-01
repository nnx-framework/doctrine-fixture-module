<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

use Doctrine\Common\Persistence\ObjectManager;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainerInterface;

/**
 * Interface SimpleFixtureMetadataBuilderInterface
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
interface SimpleFixtureMetadataBuilderInterface
{
    /**
     * Подготавливает метаданные для SimpleFixture
     *
     * @param DataContainerInterface $dataContainer
     * @param                        $entityClassName
     * @param ObjectManager          $objectManager
     *
     * @return SimpleFixtureMetadataInterface
     */
    public function buildMetadata(DataContainerInterface $dataContainer, $entityClassName, ObjectManager $objectManager);
}
