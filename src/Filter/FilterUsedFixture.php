<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Filter;

use Doctrine\Fixture\Fixture;
use Doctrine\Fixture\Filter\Filter;
use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;
use Nnx\DoctrineFixtureModule\FilterUsedFixtureService\FilterUsedFixtureServiceInterface;

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
     * Сервис позволяющий получить информацию о уже выполенных фикстурах
     *
     * @var FilterUsedFixtureServiceInterface
     */
    protected $filterUsedFixtureService;

    /**
     * FilterUsedFixture constructor.
     *
     * @param ExecutorInterface                 $contextExecutor
     * @param FilterUsedFixtureServiceInterface $filterUsedFixtureService
     */
    public function __construct(ExecutorInterface $contextExecutor, FilterUsedFixtureServiceInterface $filterUsedFixtureService)
    {
        $this->setFilterUsedFixtureService($filterUsedFixtureService);
        $this->setContextExecutor($contextExecutor);
    }


    /**
     * @inheritDoc
     */
    public function accept(Fixture $fixture)
    {
        return !$this->getFilterUsedFixtureService()->isUsedFixture($fixture, $this->getContextExecutor());
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

    /**
     * Возвращает сервис позволяющий получить информацию о уже выполенных фикстурах
     *
     * @return FilterUsedFixtureServiceInterface
     */
    public function getFilterUsedFixtureService()
    {
        return $this->filterUsedFixtureService;
    }

    /**
     *  Устанавливает сервис позволяющий получить информацию о уже выполенных фикстурах
     *
     * @param FilterUsedFixtureServiceInterface $filterUsedFixtureService
     *
     * @return $this
     */
    public function setFilterUsedFixtureService($filterUsedFixtureService)
    {
        $this->filterUsedFixtureService = $filterUsedFixtureService;

        return $this;
    }
}
