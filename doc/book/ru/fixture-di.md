# Внедрение зависимостей в фикстуры

Класс явялется фикстурой, если он реализует интерфейс \Doctrine\Fixture\Fixture. В интерфейсе определены, два
метода import и purge. При использование фикстур в реальных условиях, зачастую возникает потребность, использовать 
различные компоненты из приложения. Например в фикстурах может потребоваться ObjectManager Doctrine2, или сервис для 
загрузки данных из xml формата. Далее будут описываться способ внедрения зависимостей в фикстуры.

# Внедрение зависимостей через FixtureInitializer

## Быстрый старт

- Создать Aware интерфейс. Если фикстура реализует данный интерфейс, то значит в нее требуется внедрить зависиомость
    - Aware интерфейс, должен определять setter'ы, позволяющие передать зависимость
    
```php

interface DependencyServiceAwareInterface
{
    function setDependencyService(DependencyService $dependencyService);
}

```

- Создать FixtureInitializer
    - Создать класс реализующий интерфейс Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerInterface
    - В качестве аргументов constructor указать сервисы которые необходимо внедрить в фикстуру
    - В классе реализовать метод getSubscribedEvents, возвращающий массив с двумя значениями "import" и "purge"
    - Реализовать в классе два метода import и purge
    - В методах import и purge проверяется, что если фикстура реализует заданный Aware интерфейс, то в нее устанавливается требуемая зависимость
    
```php

use Doctrine\Fixture\Event\ImportFixtureEventListener;
use Doctrine\Fixture\Event\PurgeFixtureEventListener;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerInterface;

class MyFixtureInitializer implements
    FixtureInitializerInterface,
    ImportFixtureEventListener,
    PurgeFixtureEventListener
{
    /**
     * Constructor.
     *
     * @param DependencyService $dependencyService
     */
    public function __construct(DependencyService $dependencyService)
    {
        $this->dependencyService = $dependencyService;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            ImportFixtureEventListener::IMPORT,
            PurgeFixtureEventListener::PURGE,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function purge(FixtureEvent $event)
    {
        if ( ! ($fixture instanceof MyServiceAwareInterface)) {
            return;
        }

        $fixture->setDependencyService($this->dependencyService);
    }

    /**
     * {@inheritdoc}
     */
    public function import(FixtureEvent $event)
    {
        if ( ! ($fixture instanceof MyServiceAwareInterface)) {
            return;
        }

        $fixture->setDependencyService($this->dependencyService);
    }
}
```

- Создать фабрику для FixtureInitializer
    - Создать стандартную фабрику (реализует интерфейс \Zend\ServiceManager\FactoryInterface )
    - Из ServiceLocator (необходимо помнить что serviceLocator в фабриках используемых для менеджеров плагинов, является самим менеджером плагинов), получить необходимые зависимости
    - Создать FixtureInitializer и передать в конструктор необходимые зависимости

```php

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MyFixtureInitializerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appServiceLocator = $serviceLocator instanceof AbstractPluginManager ? $serviceLocator->getServiceLocator() : $serviceLocator;
        
        /** @var DependencyService $dependencyService */
        $dependencyService = $appServiceLocator->get(DependencyService::class);
        
        return new MyFixtureInitializer($dependencyService);
    }
}


```

- Зарегестрировать FixtureInitializer в менеджере плагинов \Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface
    - В секцию ['nnx_fixture_initializer']['factories'] конфигурационных файлов  приложения, добавить созданный FixtureInitializer
    
```php 

return [
    'nnx_fixture_initializer' => [
        'factories'          => [
            MyFixtureInitializer::class => MyFixtureInitializerFactory::class,
        ]
    ],
];

```

- Добавить FixtureInitializer в список тех FixtureInitializer, которые запускаются для фикстур
    - Добавить в секцию ['nnx_doctrine_fixture_module']['fixtureInitializer'] имя FixtureInitializer
    
```php

return [
    'nnx_doctrine_fixture_module' => [
        'fixtureInitializer' => [
            MyFixtureInitializer::class
        ]
    ],
];

```


## Описание FixtureInitializer

Для внедрения зависимостей используется модификация паттерна "Method Injection", а именно "Interface Injection".
Непосредственно после создания фикстуры, запускаются FixtureInitializer. 

FixtureInitializer это класс реализующий \Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerInterface.
Интерфейс \Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerInterface, расширяет \Doctrine\Common\EventSubscriber.

Таким образом в классе FixtureInitializer, необходимо реализовать метод getSubscribedEvents, в котором нужно вернуть, 
массив с именами событий, на которые подписываемся. 

Для реализации FixtureInitializer, необходимо подписаться на два события 

- import - событие бросаемое, когда происходит запуск процесса импорта
- purge - событие бросаемое, когда происходит заупуск процесса удаления данных

Пример FixtureInitializer

```php

use Doctrine\Fixture\Event\ImportFixtureEventListener;
use Doctrine\Fixture\Event\PurgeFixtureEventListener;
use Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerInterface;

class MyFixtureInitializer implements
    FixtureInitializerInterface,
    ImportFixtureEventListener,
    PurgeFixtureEventListener
{
    /**
     * Constructor.
     *
     * @param DependencyService $dependencyService
     */
    public function __construct(DependencyService $dependencyService)
    {
        $this->dependencyService = $dependencyService;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            ImportFixtureEventListener::IMPORT,
            PurgeFixtureEventListener::PURGE,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function purge(FixtureEvent $event)
    {
        if ( ! ($fixture instanceof MyServiceAwareInterface)) {
            return;
        }

        $fixture->setDependencyService($this->dependencyService);
    }

    /**
     * {@inheritdoc}
     */
    public function import(FixtureEvent $event)
    {
        if ( ! ($fixture instanceof MyServiceAwareInterface)) {
            return;
        }

        $fixture->setDependencyService($this->dependencyService);
    }
}



```

В фикстуре необходимо реализовать два метода import и purge. В методах обычно происходит провекра, того что фикстура, 
реализует заданный интерфейс. В случае если фикстура, интерфейс реализует, то происходит внедрение соотвествующей зависимости
в фиктсуру.

## Регистрация FixtureInitializer

Для работы с FixtureInitializer используется специальный менеджер плагинов \Nnx\DoctrineFixtureModule\FixtureInitializer\FixtureInitializerManagerInterface.
Для регистрации FixtureInitializer в менеджере плагинов, необходимо добавить соответствующий плагин в секцию nnx_fixture_initializer.

```php

return [
    'nnx_fixture_initializer' => [
        'invokables'         => [

        ],
        'factories'          => [
            MyFixtureInitializer::class => MyFixtureInitializerFactory::class,
        ],
        'abstract_factories' => [

        ],
        'shared'             => [

        ]
    ],
];

```

FixtureInitializer бывают двух типов:

- Initializer для контекста - такие FixtureInitializer, создаются и подписываются на события import и purge, в качестве аргумента, передаются данные переданные в аргументах import и purge , объекта имплементирующего \Nnx\DoctrineFixtureModule\Executor\ExecutorInterface
- Статические Initializer - такие FixtureInitializer, не создаются каждый раз заново, также они подписываются единожды на события import и purge всех FixtureInitializer

### Добавление Initializer для контекста

Для добавления Initializer для контекста, необходимо в секции ['nnx_doctrine_fixture_module']['contextInitializer'] добавить
имя Initializera'a (по этому имене он будет получен из \Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface)


### Добавление статического Initializer

Для добавления Initializer для контекста, необходимо в секции ['nnx_doctrine_fixture_module']['fixtureInitializer'] добавить
имя Initializera'a (по этому имене он будет получен из \Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface)
