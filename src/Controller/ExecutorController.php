<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Controller;

use Nnx\DoctrineFixtureModule\Event\ExecutorDispatcherEvent;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Listener\ExecutorDispatcherInterface;
use Nnx\DoctrineFixtureModule\Utils\ManagerRegistryProviderInterface;
use Zend\Mvc\Controller\AbstractConsoleController;
use Zend\Console\Request as ConsoleRequest;
use Zend\View\Model\ConsoleModel;

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
     * Запук Executor'a
     *
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
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

        $contextData = [];
        $objectManagerName = $request->getParam('object-manager', null);
        if (null !== $objectManagerName) {
            $contextData['objectManagerName'] = $objectManagerName;
        }

        $executor = $this->getFixtureExecutorManager()->get($executorName);

        $console = $this->getConsole();
        $console->writeLine(sprintf('Run fixture executor %s', $executorName));

        $eventSharedManager = $this->getEventManager()->getSharedManager();
        $listener = $eventSharedManager->attach(
            ExecutorDispatcherInterface::class,
            ExecutorDispatcherEvent::class,
            function (ExecutorDispatcherEvent $e) use ($console) {
                $console->writeLine(sprintf('Execute fixture: %s', get_class($e->getFixture())));
            }
        );

        $console->writeLine("\n");

        switch ($method) {
            case 'import': {
                $executor->import($contextData);
                break;
            }
            case 'purge': {
                $executor->purge($contextData);
                break;
            }
        }
        $eventSharedManager->detach(ExecutorDispatcherInterface::class, $listener);

        return [
            ConsoleModel::RESULT => 'All fixture completed'
        ];
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
