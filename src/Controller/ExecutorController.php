<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Controller;

use Nnx\DoctrineFixtureModule\Event\ExecutorDispatcherEvent;
use Nnx\DoctrineFixtureModule\Executor\ClassListFixtureExecutor;
use Nnx\DoctrineFixtureModule\Executor\ExecutorInterface;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Listener\ExecutorDispatcherInterface;
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
     * @param FixtureExecutorManagerInterface $fixtureExecutorManager
     */
    public function __construct(FixtureExecutorManagerInterface $fixtureExecutorManager)
    {
        $this->setFixtureExecutorManager($fixtureExecutorManager);
    }

    /**
     * Запускает фикстуры
     *
     *
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     */
    public function executeFixtureAction()
    {
        $method = $this->getExecutorMethod();

        $contextData = [];
        $objectManagerName = $this->getObjectManagerName();

        if (null !== $objectManagerName) {
            $contextData['objectManagerName'] = $objectManagerName;
        }

        $classList = $this->getFixtureClassList();

        $creationOptions = [
            'classList' => $classList
        ];
        $executor = $this->getFixtureExecutorManager()->get(ClassListFixtureExecutor::class, $creationOptions);

        $this->runFixture($executor, $method, $contextData);


        return [
            ConsoleModel::RESULT => 'All fixture completed'
        ];
    }

    /**
     * Возвращает список фикстур которые необходимо выпонить
     *
     * @return array
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     */
    protected function getFixtureClassList()
    {
        $request = $this->getConsoleRequest();
        $classListStr = preg_replace('/ {2,}/', ' ', trim($request->getParam('fixtureClassName', '')));

        return explode(' ', $classListStr);
    }

    /**
     * Возвращает метод для Executor'a
     *
     * @return string
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     */
    public function getExecutorMethod()
    {
        $request = $this->getConsoleRequest();

        $method = $request->getParam('method', null);
        if (null === $method) {
            $errMsg = 'Executor method not defined';
            throw new Exception\RuntimeException($errMsg);
        }
        $normalizeMethod = strtolower($method);
        if (!in_array($normalizeMethod, $this->allowedMethod, true)) {
            $errMsg = sprintf('Invalid executor method %s', $method);
            throw new Exception\RuntimeException($errMsg);
        }

        return $normalizeMethod;
    }

    /**
     * Возвращает имя ObjectManager'a
     *
     * @return string|null
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     */
    protected function getObjectManagerName()
    {
        $request = $this->getConsoleRequest();

        return $request->getParam('object-manager', null);
    }

    /**
     * Возвращает консольный запрос
     *
     * @return ConsoleRequest
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     */
    protected function getConsoleRequest()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            $errMsg = 'Request is not console';
            throw new Exception\RuntimeException($errMsg);
        }

        return $request;
    }

    /**
     * Запук Executor'a
     *
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\Event\Exception\RuntimeException
     */
    public function runExecutorAction()
    {
        $executorName = $this->getExecutorName();
        $method = $this->getExecutorMethod();

        $contextData = [];
        $objectManagerName = $this->getObjectManagerName();
        if (null !== $objectManagerName) {
            $contextData['objectManagerName'] = $objectManagerName;
        }

        $executor = $this->getFixtureExecutorManager()->get($executorName);

        $this->runFixture($executor, $method, $contextData);

        return [
            ConsoleModel::RESULT => 'All fixture completed'
        ];
    }

    /**
     * Запуск выполнения фикстур
     *
     * @param ExecutorInterface $executor
     * @param                   $method
     * @param array             $contextData
     */
    protected function runFixture(ExecutorInterface $executor, $method, array $contextData = [])
    {
        $console = $this->getConsole();
        $console->writeLine(sprintf('Run fixture executor %s', $executor->getName()));

        $eventSharedManager = $this->getEventManager()->getSharedManager();
        $listener = $eventSharedManager->attach(
            ExecutorDispatcherInterface::class,
            ExecutorDispatcherEvent::FINISH_FIXTURE_EVENT,
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
    }


    /**
     * Вовзращает имя Executor'a
     *
     * @return string
     * @throws \Nnx\DoctrineFixtureModule\Controller\Exception\RuntimeException
     */
    public function getExecutorName()
    {
        $request = $this->getConsoleRequest();
        $executorName = $request->getParam('executorName', null);

        if (null === $executorName) {
            $errMsg = 'Executor name is not defined';
            throw new Exception\RuntimeException($errMsg);
        }

        return $executorName;
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
