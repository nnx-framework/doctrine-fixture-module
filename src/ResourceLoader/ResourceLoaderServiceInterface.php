<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\ResourceLoader;

use Doctrine\Fixture\Fixture;

/**
 * Interface ResourceLoaderServiceInterface
 *
 * @package Nnx\DoctrineFixtureModule\ResourceLoader
 */
interface ResourceLoaderServiceInterface
{
    /**
     * Загружает ресурсы для фикстуры
     *
     * @param Fixture $fixture
     *
     * @return mixed
     */
    public function loadResourceForFixture(Fixture $fixture);
}
