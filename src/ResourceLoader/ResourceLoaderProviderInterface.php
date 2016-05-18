<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\ResourceLoader;

/**
 * Interface ResourceLoaderProviderInterface
 *
 * @package Nnx\DoctrineFixtureModule\ResourceLoader
 */
interface ResourceLoaderProviderInterface
{
    /**
     * Возвращает настройки для загрузчика ресуросв фикстур
     *
     * @return array
     */
    public function getResourceLoaderConfig();
}
