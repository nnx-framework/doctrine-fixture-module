<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FilterUsedFixtureService;

use Nnx\DoctrineFixtureModule\Event\ExecutorDispatcherEvent;
use Nnx\DoctrineFixtureModule\Listener\AbstractExecutorListener;

/**
 * Class FilterUsedFixtureListener
 *
 * @package Nnx\DoctrineFixtureModule\FilterUsedFixtureService
 */
class FilterUsedFixtureListener extends AbstractExecutorListener
{

    /**
     * Сервис для реализации фильтрации фикстур
     *
     * @var FilterUsedFixtureServiceInterface
     */
    protected $filterUsedFixtureService;

    /**
     * FilterUsedFixtureListener constructor.
     *
     * @param FilterUsedFixtureServiceInterface $filterUsedFixtureService
     */
    public function __construct(FilterUsedFixtureServiceInterface $filterUsedFixtureService)
    {
        $this->setFilterUsedFixtureService($filterUsedFixtureService);
    }

    /**
     * Обработчик события возникающего при окончание работы фикстуры
     *
     * @param ExecutorDispatcherEvent $e
     *
     * @return void
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function onFinishFixtureHandler(ExecutorDispatcherEvent $e)
    {
        $filterUsedFixtureService = $this->getFilterUsedFixtureService();
        $executor = $e->getExecutor();
        if ($filterUsedFixtureService->hasFilterUsedFixture($executor)) {
            $fixture = $e->getFixture();
            $filterUsedFixtureService->markFixture($executor, $fixture);
        }
    }



    /**
     * Возвращает сервис для реализации фильтрации фикстур
     *
     * @return FilterUsedFixtureServiceInterface
     */
    public function getFilterUsedFixtureService()
    {
        return $this->filterUsedFixtureService;
    }

    /**
     * Устанавливает сервис для реализации фильтрации фикстур
     *
     * @param FilterUsedFixtureServiceInterface $filterUsedFixtureService
     *
     * @return $this
     */
    public function setFilterUsedFixtureService(FilterUsedFixtureServiceInterface $filterUsedFixtureService)
    {
        $this->filterUsedFixtureService = $filterUsedFixtureService;

        return $this;
    }
}
