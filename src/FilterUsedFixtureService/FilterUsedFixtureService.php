<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FilterUsedFixtureService;

use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;
use Doctrine\Fixture\Filter\Filter;
use Doctrine\Fixture\Filter\ChainFilter;
use Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use SplObjectStorage;
use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\Entity\UsedFixture;

/**
 * Class FilterUsedFixtureService
 *
 * @package Nnx\DoctrineFixtureModule\FilterUsedFixtureService
 */
class FilterUsedFixtureService implements FilterUsedFixtureServiceInterface
{

    /**
     * Информация о Executor'aх которые используют фильтр FilterUsedFixture
     *
     * @var SplObjectStorage
     */
    protected $filterUsedFixtureByExecutor;

    /**
     * Компонент для управления получения ManagerRegistry
     *
     * @var ManagerRegistryProviderInterface
     */
    protected $managerRegistryProvider;

    /**
     * Прототип для сущности содержащей информацию о отработанных фикстурах
     *
     * @var UsedFixture
     */
    protected $usedFixtureEntityPrototype;

    /**
     * В зависимости передается не ManagerRegistry, а провайдер позволяющий получить его по требованию. Это сделанно
     * специально, что бы предотвратить получение ManagerRegistry при старте модуля
     *
     *
     * FilterUsedFixtureService constructor.
     *
     * @param ManagerRegistryProviderInterface $managerRegistryProvider
     *
     */
    public function __construct(ManagerRegistryProviderInterface $managerRegistryProvider)
    {
        $this->setManagerRegistryProvider($managerRegistryProvider);
        $this->filterUsedFixtureByExecutor = new SplObjectStorage();
    }

    /**
     * Проверяет, есть  ли фильтр по использованным фикстурам
     *
     * @param ExecutorInterface $executor
     *
     *
     * @return boolean
     */
    public function hasFilterUsedFixture(ExecutorInterface $executor)
    {
        if ($this->filterUsedFixtureByExecutor->offsetExists($executor)) {
            return $this->filterUsedFixtureByExecutor->offsetGet($executor);
        }

        $filter = $executor->getFilter();

        $hasFilterUsedFixture = $this->detectFilterUsedFixture($filter);
        $this->filterUsedFixtureByExecutor->offsetSet($executor, $hasFilterUsedFixture);

        return $hasFilterUsedFixture;
    }

    /**
     * Проверяет используется ли FilterUsedFixture
     *
     * @param Filter $filter
     *
     * @return bool
     */
    protected function detectFilterUsedFixture(Filter $filter)
    {
        if ($filter instanceof ChainFilter) {
            /** @var Filter[] $filters */
            $filters = $filter->getFilterList();
            foreach ($filters as $currentFilter) {
                if ($this->detectFilterUsedFixture($currentFilter)) {
                    return true;
                }
            }
            return false;
        }


        return $filter instanceof FilterUsedFixture;
    }

    /**
     * Метит фикстуру как использованную
     *
     * @param ExecutorInterface $executor
     * @param Fixture           $fixture
     *
     * @throws \UnexpectedValueException
     */
    public function markFixture(ExecutorInterface $executor, Fixture $fixture)
    {
        $objectManager = $this->getManagerRegistryProvider()->getManagerRegistry()->getManager();


        $executorName = $executor->getName();
        $fixtureClassName =  get_class($fixture);

        $markers = $objectManager->getRepository(UsedFixture::class)->findBy([
            'executorName' => $executorName,
            'fixtureClassName' => $fixtureClassName
        ]);

        if (count($markers) > 0) {
            return;
        }

        $usedFixtureEntity = clone $this->getUsedFixtureEntityPrototype();
        $usedFixtureEntity->setExecutorName($executorName);
        $usedFixtureEntity->setFixtureClassName($fixtureClassName);

        $objectManager->persist($usedFixtureEntity);
        $objectManager->flush();
    }


    /**
     * Проверяет, выполнялась ли данная фикстура
     *
     * @param Fixture           $fixture
     * @param ExecutorInterface $executor
     *
     * @return boolean
     * @throws \UnexpectedValueException
     */
    public function isUsedFixture(Fixture $fixture, ExecutorInterface $executor)
    {
        $objectManager = $this->getManagerRegistryProvider()->getManagerRegistry()->getManager();

        $executorName = $executor->getName();
        $fixtureClassName =  get_class($fixture);

        $markers = $objectManager->getRepository(UsedFixture::class)->findBy([
            'executorName' => $executorName,
            'fixtureClassName' => $fixtureClassName
        ]);

        return count($markers) > 0;
    }


    /**
     * Возвращает компонент позволяющий получить ManagerRegistry
     *
     * @return ManagerRegistryProviderInterface
     */
    public function getManagerRegistryProvider()
    {
        return $this->managerRegistryProvider;
    }

    /**
     * Устанавливает компонент позволяющий получить ManagerRegistry
     *
     * @param ManagerRegistryProviderInterface $managerRegistryProvider
     *
     * @return $this
     */
    public function setManagerRegistryProvider(ManagerRegistryProviderInterface $managerRegistryProvider)
    {
        $this->managerRegistryProvider = $managerRegistryProvider;

        return $this;
    }

    /**
     * Возвращает прототип для сущности содержащей информацию о отработанных фикстурах
     *
     * @return UsedFixture
     */
    public function getUsedFixtureEntityPrototype()
    {
        if (null === $this->usedFixtureEntityPrototype) {
            $usedFixtureEntityPrototype = new UsedFixture();
            $this->setUsedFixtureEntityPrototype($usedFixtureEntityPrototype);
        }
        return $this->usedFixtureEntityPrototype;
    }

    /**
     * Устанавливает прототип для сущности содержащей информацию о отработанных фикстурах
     *
     * @param UsedFixture $usedFixtureEntityPrototype
     *
     * @return $this
     */
    public function setUsedFixtureEntityPrototype(UsedFixture $usedFixtureEntityPrototype)
    {
        $this->usedFixtureEntityPrototype = $usedFixtureEntityPrototype;

        return $this;
    }
}
