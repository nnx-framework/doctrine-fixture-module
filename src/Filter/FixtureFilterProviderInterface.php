<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Filter;

/**
 * Interface FixtureFilterProviderInterface
 *
 * @package Nnx\DoctrineFixtureModule\Filter
 */
interface FixtureFilterProviderInterface
{
    /**
     * Возвращает настройки фильтров фикстур
     *
     * @return array
     */
    public function getFixtureFilterConfig();
}
