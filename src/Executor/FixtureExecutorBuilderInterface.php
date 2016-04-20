<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Doctrine\Fixture\Executor as FixtureExecutor;
use Doctrine\Fixture\Configuration;

/**
 * Interface FixtureExecutorBuilderInterface
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
interface FixtureExecutorBuilderInterface
{

    /**
     * Создает копмонент отвечающий за выполнение фикстур
     *
     * @param Configuration     $configuration
     *
     * @param ExecutorInterface $executor
     *
     * @return FixtureExecutor
     */
    public function build(Configuration $configuration, ExecutorInterface $executor);
}
