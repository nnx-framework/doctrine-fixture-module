<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FilterUsedFixtureService;

use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;
use Doctrine\Fixture\Fixture;

/**
 * Interface FilterUsedFixtureServiceInterface
 *
 * @package Nnx\DoctrineFixtureModule\FilterUsedFixtureService
 */
interface FilterUsedFixtureServiceInterface
{
    /**
     * Проверяет, есть  ли фильтр по использованным фикстурам
     *
     * @param ExecutorInterface $executor
     *
     *
     * @return boolean
     */
    public function hasFilterUsedFixture(ExecutorInterface $executor);

    /**
     * Метит фикстуру как использованную
     *
     * @param ExecutorInterface $executor
     * @param Fixture           $fixture
     */
    public function markFixture(ExecutorInterface $executor, Fixture $fixture);

    /**
     * Проверяет, выполнялась ли данная фикстура
     *
     * @param Fixture  $fixture
     * @param ExecutorInterface $executor
     *
     * @return boolean
     */
    public function isUsedFixture(Fixture $fixture, ExecutorInterface $executor);
}
