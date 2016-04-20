<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

/**
 * Interface FixtureExecutorProviderInterface
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
interface FixtureExecutorProviderInterface
{
    /**
     * Возвращает настройки фильтров фикстур
     *
     * @return array
     */
    public function getFixtureExecutorConfig();
}
