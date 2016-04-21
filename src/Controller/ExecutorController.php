<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Entity;

use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use Zend\Mvc\Controller\AbstractConsoleController;

/**
 * Class ExecutorController
 *
 * @package Nnx\DoctrineFixtureModule\Entity
 */
class ExecutorController extends AbstractConsoleController
{
    /**
     * Провайдер для получения ManagerRegistry
     *
     * @var ManagerRegistryProviderInterface
     */
    protected $managerRegistryProvider;

    /**
     * Менеджер для получения Executor'ов
     *
     * @var FixtureExecutorManagerInterface
     */
    protected $fixtureExecutorManager;

    /**
     * ExecutorController constructor.
     *
     * @param ManagerRegistryProviderInterface $managerRegistryProvider
     * @param FixtureExecutorManagerInterface  $fixtureExecutorManager
     */
    public function __construct(ManagerRegistryProviderInterface $managerRegistryProvider, FixtureExecutorManagerInterface $fixtureExecutorManager)
    {
        $this->setManagerRegistryProvider($managerRegistryProvider);
        $this->setFixtureExecutorManager($fixtureExecutorManager);
    }

    /**
     *
     *
     */
    public function executeFixtureAction()
    {
    }


    /**
     *
     *
     */
    public function runExecutorAction()
    {
    }

    /**
     * Возвращает провайдер для получения ManagerRegistry
     *
     * @return ManagerRegistryProviderInterface
     */
    public function getManagerRegistryProvider()
    {
        return $this->managerRegistryProvider;
    }

    /**
     * Устанавливает провайдер для получения ManagerRegistry
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
     * Возвращает менеджер для получения Executor'ов
     *
     * @return FixtureExecutorManagerInterface
     */
    public function getFixtureExecutorManager()
    {
        return $this->fixtureExecutorManager;
    }

    /**
     * Устанавливает менеджер для получения Executor'ов
     *
     * @param FixtureExecutorManagerInterface $fixtureExecutorManager
     *
     * @return $this
     */
    public function setFixtureExecutorManager($fixtureExecutorManager)
    {
        $this->fixtureExecutorManager = $fixtureExecutorManager;

        return $this;
    }
}
