<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Loader;

/**
 * Class ModuleOptions
 *
 * @package Nnx\DoctrineFixtureModule\Loader
 */
interface FixtureLoaderProviderInterface
{
    /**
     * Возвращает настройки загрузчиков фикстур
     *
     * @return array
     */
    public function getFixtureLoaderConfig();
}
