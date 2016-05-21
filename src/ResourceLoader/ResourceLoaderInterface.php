<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\ResourceLoader;

use Doctrine\Fixture\Fixture;

/**
 * Interface ResourceLoaderInterface
 *
 * @package Nnx\DoctrineFixtureModule\ResourceLoader
 */
interface ResourceLoaderInterface
{
    /**
     * Загружает ресурсы для фикстуры
     *
     * @param Fixture $fixture
     *
     * @return void
     */
    public function loadResourceForFixture(Fixture $fixture);
}
