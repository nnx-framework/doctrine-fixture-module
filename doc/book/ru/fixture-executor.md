# Компонент для запуска фикстур

Executor фикстур - класс, имплементирующий \Nnx\DoctrineFixtureModule\Executor\ExecutorInterface, отвечающий за запуск
фикстур.


Для работы с executor'ами фикстур используется специальный менеджер плагинов - \Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface.
Пример получения  executor'a фикстур в фабрике:

```php

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface;
use Nnx\DoctrineFixtureModule\Executor\Executor;

class ExampleFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FixtureExecutorManagerInterface $fixtureFilterManager */
        $fixtureExecutorManager = $serviceLocator->get(FixtureExecutorManagerInterface::class);

        $fixtureExecutor = $fixtureExecutorManager->get(Executor::class);

    }
}

```


Реализованы следующие Executor'ы

Executor                                                     |Описание
-------------------------------------------------------------|-------------------
\Nnx\DoctrineFixtureModule\Executor\Executor                 |Основной Executor
\Nnx\DoctrineFixtureModule\Executor\ClassListFixtureExecutor |Позволяет запускать выполнить заданный набор фикстур


Каждый из этих executor зарегистрирован в менеджере плагинов Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface.

## Executor \Nnx\DoctrineFixtureModule\Executor\Executor 

Базовый Executor, подходит для большинства случаев. При создание через Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface,
вторым аргументом можно указать набор настроек.

Настройки \Nnx\DoctrineFixtureModule\Executor\Executor

Имя параметра  |Описание
---------------|-------------------
configuration  |Объект инстанцированный от класса \Doctrine\Fixture\Configuration (или его потомков). Содержитнастройки необходимые для Executor'a
builder        |Объект реализующий \Nnx\DoctrineFixtureModule\Executor\FixtureExecutorBuilderInterface. Отвечает за создание и настройку \Doctrine\Fixture\Executor
fixturesLoader |Имя загрузчика фикстур. Загрузчик фикстур получается с помощью \Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface
filter         |Имя фильтра фикстур. Фильтр фикстур получается с помощью \Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface
name           |Имя Executor'a. Обычно содержит имя, заданное в секции конфигов ['nnx_doctrine_fixture_module]['executors][Имя Executor'a]



## Executor \Nnx\DoctrineFixtureModule\Executor\Executor 

Executor предназначенный для выполнения списка заданных фикстур. При создание через Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface,
вторым аргументом можно указать набор настроек.

Настройки \Nnx\DoctrineFixtureModule\Executor\Executor

Имя параметра  |Описание
---------------|-------------------
classList      |Массив содержащий имена классов фикстур
