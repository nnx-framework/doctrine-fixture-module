<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Controller;

use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use Zend\Mvc\Controller\AbstractConsoleController;
use Zend\Console\Request as ConsoleRequest;

/**
 * Class ExecutorController
 *
 * @package Nnx\DoctrineFixtureModule\Entity
 *
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
     * Разрешенные методы
     *
     * @var array
     */
    protected $allowedMethod = [
        'import',
        'purge'
    ];

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
        $request = $this->getRequest();
    }


    /**
     *
     *
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     */
    public function runExecutorAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            $errMsg = 'Request is not console';
            throw new Exception\RuntimeException($errMsg);
        }

        $executorName = $request->getParam('executorName', null);
        if (null === $executorName) {
            $errMsg = 'Executor name is not defined';
            throw new Exception\RuntimeException($errMsg);
        }
        $method       = $request->getParam('method', null);
        if (null === $method) {
            $errMsg = 'Executor method not defined';
            throw new Exception\RuntimeException($errMsg);
        }
        $normalizeMethod = strtolower($method);
        if (!in_array($normalizeMethod, $this->allowedMethod, true)) {
            $errMsg = sprintf('Invalid executor method %s', $method);
            throw new Exception\RuntimeException($errMsg);
        }

        $objectManager = $request->getParam('object-manager', null);

        $executor = $this->getFixtureExecutorManager()->get($executorName);

        switch ($method) {
            case 'import': {
                $executor->import();
                break;
            }
            case 'purge': {
                $executor->purge();
                break;
            }
        }
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
