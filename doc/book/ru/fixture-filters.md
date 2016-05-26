# Фильтры фикстур

Фильтр фикстур — класс, имплементирующий \Doctrine\Fixture\Filter\Filter. Определяет условия того, какие фикстуры должны быть выполнены. 

Для работы с фильтрами фикстур используется специальный менеджер плагинов — \Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface.
Пример получения фильтров фикстур в фабрике:

```php

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface;
use Doctrine\Fixture\Filter\GroupedFilter;


class ExampleFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FixtureFilterManagerInterface $fixtureFilterManager */
        $fixtureFilterManager = $serviceLocator->get(FixtureFilterManagerInterface::class);

        $fixtureFilter = $fixtureFilterManager->get(GroupedFilter::class);

    }
}

```

Поддерживаются следующие фильтры:

Фильтр                                             |Описание
---------------------------------------------------|-------------------
\Doctrine\Fixture\Filter\ChainFilter               |Позволяет объединить фильтры в цепочку
\Doctrine\Fixture\Filter\GroupedFilter             |Позволяет запускать фикстуры, принадлежащие к определенной группе
\Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture|Обеспечивает возможность не выполнять фикстуры повторно


Каждый из этих фильтров фикстур зарегистрирован в менеджере плагинов \Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface.

## Фильтр \Doctrine\Fixture\Filter\ChainFilter

Объеденяет фильтры в цепочку.

Пример использования:

```php


$testFixture = new \Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\FooFixture();

/** @var \Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface $fixtureFilterManager */
$fixtureFilterManager = $serviceLocator->get(\Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface::class);

$options = [
    'filterList' => [
        $fixtureFilterManager->get(\Doctrine\Fixture\Filter\GroupedFilter::class, [
            'allowedGroupList' => [
                'group1'
            ]
        ]),
        $fixtureFilterManager->get(\Doctrine\Fixture\Filter\GroupedFilter::class, [
            'allowedGroupList' => [
                'group2'
            ]
        ]),
    ]
];

if ($fixtureFilterManager->get(\Doctrine\Fixture\Filter\ChainFilter::class, $options)->accept($testFixture)) {
    $testFixture->import();
}

```

При создании экземпляра фильтра фикстур \Doctrine\Fixture\Filter\ChainFilter через плагин-менеджер в опциях с 
помощью параметра filterList можно указать массив объектов фильтров фикстур.

После вызова метода accept у экземпляра объекта \Doctrine\Fixture\Filter\ChainFilter у всех фильтров фикстур, образующих
цепочку, будет вызван аналогичный метод и произведена проверка, нужно ли выполнять данную фикстуру.

## Фильтр \Doctrine\Fixture\Filter\GroupedFilter

Для работы с группами фикстур необходимо, чтобы фикстуры реализовывали интерфейс \Doctrine\Fixture\Filter\GroupdFixteure.
Метод getGroupList должен возвращать массив с именами групп, к которым относится фикстура.

Пример фикстуры:

```php
namespace Doctrine\Test\Mock\Grouped;

use Doctrine\Fixture\Filter\GroupedFixture;

class FixtureA implements GroupedFixture
{
    /**
     * {@inheritdoc}
     */
    public function getGroupList()
    {
        return array('test', 'another_test');
    }

    /**
     * {@inheritdoc}
     */
    public function import()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function purge()
    {
    }
}


```

При создании экземпляра фильтра фикстур \Doctrine\Fixture\Filter\GroupedFilter через плагин-менеджер в опциях с 
помощью параметра allowedGroupList можно указать список групп, к которым должна относится фикстура, чтобы она была 
выполнена.

Также можно указать параметр onlyImplementors (значение по умолчанию — false). Если этот параметр имеет значение true, то
все фикстуры, которые не реализуют интерфейс \Doctrine\Fixture\Filter\GroupdFixteure, не будут выполнены.


```php


$testFixture = new \Doctrine\Test\Mock\Grouped\FixtureA();

/** @var \Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface $fixtureFilterManager */
$fixtureFilterManager = $serviceLocator->get(\Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface::class);

$options = [
    'allowedGroupList' => [
        'another_test'
    ],
    'onlyImplementors' => false
];

if ($fixtureFilterManager->get(\Doctrine\Fixture\Filter\GroupedFilter::class, $options)->accept($testFixture)) {
    $testFixture->import();
}

```


## Фильтр \Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture

Фильтр позволяет для конкретного Executor'a (описанного в конфигах приложения) выполнить фикстуры только один раз. Таким 
образом, повторный запуск такого Executor'a не приведет к повторному выполнению ранее выполненных фикстур, будут выполнены только новые фикстуры.

## Быстрый старт при использование \Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture

Необходимо зарегистрировать сущность для работы с \Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture. В случае если
для работы с Doctrine2 используется doctrine/doctrine-orm-module, необходимо в цепочку драйверов, которую использует
ObjectManager Doctrine2, добавить драйвер для сущностей модуля nnx/doctrine-fixture-module.

```php

return [
    'doctrine' => [
        'entitymanager' => [
            'orm_default' => [
                'configuration' => 'orm_default',
                'connection'    => 'orm_default',
            ]
        ],
        'connection' => [
            'orm_default' => [
                'configuration' => 'orm_default',
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'driver'            => 'orm_default'
            ]
        ],
        'driver' => [
            'orm_default' => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    'Nnx\\DoctrineFixtureModule\\Entity' => 'Nnx\\DoctrineFixtureModule'
                ]
            ]
        ]
    ],
];


```

###  Независимое использование фильтра \Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture

Для использования фильтра FilterUsedFixture при его создании необходимо передать Executor, в контексте которого будет запускаться фикстура.

```php

//Создаем компонент, отвечающий за запуск фикстур
//Указываем через опции его имя
/** @var \Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface $fixtureExecutorManager */
$fixtureExecutorManager = $serviceLocator->get(\Nnx\DoctrineFixtureModule\Executor\FixtureExecutorManagerInterface::class);
/** @var  \Nnx\DoctrineFixtureModule\Executor\Executor $executor  */
$executor = $fixtureExecutorManager->get(\Nnx\DoctrineFixtureModule\Executor\Executor::class, [
    'name' => 'myFixtureExecutor'
]);

//Создаем загрузчик фикстур
//Загрузчик знает, как загрузить одну тестовую фикстуру
/** @var \Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface $loaderManager */
$loaderManager = $serviceLocator->get(\Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface::class);
$options = [
    'classList' => [
        \Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\FooFixture::class
    ]
];
/** @var \Doctrine\Fixture\Loader\ClassLoader::class $loader */
$loader = $loaderManager->get(\Doctrine\Fixture\Loader\ClassLoader::class, $options);

//Создаем фильтр. Указываем через параметры, для какого Executor'a фильтр используется
/** @var \Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface $fixtureFilterManager */
$fixtureFilterManager = $serviceLocator->get(\Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface::class);
$options = [
    'contextExecutor' => $executor
];
$filter = $fixtureFilterManager->get(\Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture::class, $options);

//Устанавливаем загрузчик и фильтр
$executor->setLoader($loader);
$executor->setFilter($filter);

$executor->import();


```

После того как будет запущен процесс загрузки (или удаления) данных из фикстуры, в специальной таблице 
(@see \Nnx\DoctrineFixtureModule\Entity\UsedFixture) будет сохранена информация, о том, что для Executor'a с заданным
именем (имя параметра name, используемое в опциях при создание Executor'a), была выполнена фикстура 
 \Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\FooFixture. При повторном запуске
 данная фикстура выполняться не будет.
 
###  Использование \Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture с помощью конфигурации в приложении

Для использования \Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture с помощью конфигурации в приложении
можно руководствоваться следующим примером:

Пример конфигурации:
```php

return [
    'nnx_doctrine_fixture_module' => [
        'fixturesLoaders' => [
            'testChainFixtureLoader' => [
                [
                    'name' => ClassLoader::class,
                    'options' => [
                        'classList' => [
                            TestModule1\FooFixture::class,
                            TestModule1\BarFixture::class,
                        ]
                    ]
                ],
                [
                    'name' => DirectoryLoader::class,
                    'options' => [
                        'directory' => __DIR__ . '/../../fixtures'
                    ]
                ],
                [
                    'name' => 'childTestChain',
                ]
            ],
        ],
        'filters' => [
            'testFilterUsedFixture' => [
                [
                    'name' => FilterUsedFixture::class
                ]
            ]
        ],
        'executors' => [
            'testFilterUsedFixture' => [
                'fixturesLoader' => 'testChainFixtureLoader',
                'filter' => 'testFilterUsedFixture'
            ]

        ]
    ]
];
```

Пример использования:

```php

/** @var FixtureExecutorManagerInterface $fixtureExecutorManager */
$fixtureExecutorManager = $serviceLocator()->get(FixtureExecutorManagerInterface::class);
$executor = $fixtureExecutorManager->get('testFilterUsedFixture');
$executor->import();

```

В результате фикстуры, описанные в fixturesLoaders (секция с именем testChainFixtureLoader), будут выполнены
только один раз.

В базе данных в соответствующей таблице (@see \Nnx\DoctrineFixtureModule\Entity\UsedFixture) будет добавлена
информация о том, какие фикстуры были выполнены для Executor'a с именем testFilterUsedFixture.
