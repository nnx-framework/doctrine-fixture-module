<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

/**
 * Interface FixtureInitializerProviderInterface
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
interface FixtureInitializerProviderInterface
{
    /**
     * Возвращает настройки инициалайзеров фикстур
     *
     * @return array
     */
    public function getFixtureInitializerConfig();
}
