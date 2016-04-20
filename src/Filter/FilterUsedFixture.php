<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Filter;

use Doctrine\Fixture\Fixture;
use Doctrine\Fixture\Filter\Filter;
use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;

/**
 * Class FilterUsedFixture
 *
 * @package Nnx\DoctrineFixtureModule\Filter
 */
class FilterUsedFixture
    implements Filter
{
    /**
     * @var ExecutorInterface
     */
    protected $contextExecutor;

    /**
     * FilterUsedFixture constructor.
     *
     * @param ExecutorInterface $contextExecutor
     */
    public function __construct(ExecutorInterface $contextExecutor)
    {
        $this->setContextExecutor($contextExecutor);
    }


    /**
     * @inheritDoc
     */
    public function accept(Fixture $fixture)
    {
        return true;
    }

    /**
     * Возвращает Executor в контексте которого запущен фильтр
     *
     * @return ExecutorInterface
     */
    public function getContextExecutor()
    {
        return $this->contextExecutor;
    }

    /**
     * Устанавливает Executor в контексте которого запущен фильтр
     *
     * @param ExecutorInterface $contextExecutor
     *
     * @return $this
     */
    public function setContextExecutor(ExecutorInterface $contextExecutor)
    {
        $this->contextExecutor = $contextExecutor;

        return $this;
    }
}
