# Загрузчики фикстур

Загрузчик фикстур – класс, имплементирующий \Doctrine\Fixture\Loader\Loader, позволяющий загрузить одну или несколько фикстур.

Для работы с загрузчиками фикстур используется специальный менеджер плагинов \Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface.
Пример получения загрузчика фикстур в фабрике:

```php

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface;
use Doctrine\Fixture\Loader\ClassLoader;


class ExecutorFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\Executor\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FixtureLoaderManagerInterface $fixtureLoaderManager */
        $fixtureLoaderManager = $serviceLocator->get(FixtureLoaderManagerInterface::class);

        $fixtureLoader = $fixtureLoaderManager->get(ClassLoader::class);

    }
}

```

Модуль [doctrine/data-fixtures](https://github.com/doctrine/data-fixtures) предоставляет следующие загрузчики фикстур:

Загрузчик                                        |Описание
-------------------------------------------------|-------------------
\Doctrine\Fixture\Loader\ChainLoader             |Позволяет объединить загрузчики фикстур в цепочку
\Doctrine\Fixture\Loader\ClassLoader             |Загрузка списка классов фикстур
\Doctrine\Fixture\Loader\DirectoryLoader         |Загружает все фикстуры из заданной директории
\Doctrine\Fixture\Loader\GlobLoader              |Загружает все фикстуры из заданной директории в качестве средства получения файлов из директории используется \GlobIterator
\Doctrine\Fixture\Loader\RecursiveDirectoryLoader|Загружает все фикстуры из заданной директории, используя рекурсивный обход вложенных каталогов

Каждый из этих загрузчиков фикстур зарегестрирован в менеджере плагинов \Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface.

## Загрузчик \Doctrine\Fixture\Loader\ChainLoader 

Объединяет загрузчики фикстур в цепочку.

Пример использования:

```php

/** @var FixtureLoaderManagerInterface $loaderManager */
$loaderManager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);
$options = [
    'loaderList' => [
        $loaderManager->get(\Doctrine\Fixture\Loader\ClassLoader::class, [
            'classList' => [
                \Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\FixturesDir\FooFixture::class
            ]
        ]),
        $loaderManager->get(\Doctrine\Fixture\Loader\ClassLoader::class, [
            'classList' => [
                \Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\FixturesDir\BarFixture::class
            ]
        ])
    ]
];
/** @var \Doctrine\Fixture\Fixture[] $fixtures */
$fixtures = $loaderManager->get(\Doctrine\Fixture\Loader\ChainLoader::class, $options)->load();

```

При создании экземпляра загрузчика фикстур \Doctrine\Fixture\Loader\ChainLoader через плагин-менеджер в опциях с 
помощью параметра loaderList можно указать массив объектов-загрузчиков фикстур.

После вызова метода load у экземпляра объекта \Doctrine\Fixture\Loader\ChainLoader будут загружены все фикстуры из 
загрузчиков, образующих цепочку.


## Загрузчик \Doctrine\Fixture\Loader\ClassLoader
 
Позволяет загрузить фикстуры на основе списка переданных классов фикстур.

Пример использования:

```php

/** @var FixtureLoaderManagerInterface $loaderManager */
$loaderManager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);
$options = [
    'classList' => [
        \Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\FooFixture::class,
        \Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\BarFixture::class,
    ]
];
/** @var \Doctrine\Fixture\Fixture[] $fixtures */
$fixtures = $loaderManager->get(\Doctrine\Fixture\Loader\ClassLoader::class, $options)->load();

```
При создании экземпляра загрузчика фикстур \Doctrine\Fixture\Loader\ClassLoader через плагин-менеджер в опциях с 
помощью параметра classList можно указать массив с именами классов фикстур.

После вызова метода load у экземпляра объекта \Doctrine\Fixture\Loader\ClassLoader будут загружены все фикстуры, перечисленные в 
classList.

## Загрузчик \Doctrine\Fixture\Loader\DirectoryLoader
 
Позволяет загрузить фикстуры из заданной директории.

Пример использования:

```php

/** @var FixtureLoaderManagerInterface $loaderManager */
$loaderManager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);
$options = [
    'directory' => __DIR__ . '/../../fixture/'
];
/** @var \Doctrine\Fixture\Fixture[] $fixtures */
$fixtures = $loaderManager->get(\Doctrine\Fixture\Loader\DirectoryLoader::class, $options)->load();

```

При создании экземпляра загрузчика фикстур \Doctrine\Fixture\Loader\DirectoryLoader через плагин-менеджер в опциях с 
помощью параметра directory указывается директория, в которой располагаются файлы с фикстурами.

После вызова метода load у экземпляра объекта \Doctrine\Fixture\Loader\DirectoryLoader будут загружены все фикстуры, найденные в тех
файлах, которые расположены в директории из параметра directory.

## Загрузчик \Doctrine\Fixture\Loader\GlobLoader

Позволяет загрузить фикстуры из заданной директории. Загрузка производится с помощью GlobIterator. 

Пример использования:

```php

/** @var FixtureLoaderManagerInterface $loaderManager */
$loaderManager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);
$options = [
    'directory' => __DIR__ . '/../../fixture/'
];
/** @var \Doctrine\Fixture\Fixture[] $fixtures */
$fixtures = $loaderManager->get(\Doctrine\Fixture\Loader\DirectoryLoader::class, $options)->load();

```

При создании экземпляра загрузчика фикстур \Doctrine\Fixture\Loader\DirectoryLoader через плагин-менеджер в опциях с 
помощью параметра directory указывается директория, в которой располагаются файлы с фикстурами.

После вызова метода load у экземпляра объекта \Doctrine\Fixture\Loader\DirectoryLoader будут загружены все фикстуры, найденные в тех
файлах, которые расположены в директории из параметра directory.

## Загрузчик \Doctrine\Fixture\Loader\RecursiveDirectoryLoader

Позволяет загрузить фикстуры из заданной директории. При этом рекурсивно обходятся вложенные каталоги. Загрузка происходит с помощью RecursiveDirectoryIterator

Пример использования:

```php

/** @var FixtureLoaderManagerInterface $loaderManager */
$loaderManager = $this->getApplicationServiceLocator()->get(FixtureLoaderManagerInterface::class);
$options = [
    'directory' => __DIR__ . '/../../fixture/'
];
/** @var \Doctrine\Fixture\Fixture[] $fixtures */
$fixtures = $loaderManager->get(\Doctrine\Fixture\Loader\RecursiveDirectoryLoader::class, $options)->load();

```

При создании экземпляра загрузчика фикстур \Doctrine\Fixture\Loader\RecursiveDirectoryLoader через плагин-менеджер в опциях с 
помощью параметра directory указывается директория, в которой располагаются файлы с фикстурами.

После вызова метода load у экземпляра объекта \Doctrine\Fixture\Loader\RecursiveDirectoryLoader будут загружены все фикстуры найденные в файлах и вложенных подкаталогах в директории, указанной в параметре directory.

## Создание загрузчиков с помощью конфигов приложения

Для упрощения работы с загрузчиками их можно описать в конфиге приложения.

В секции nnx_doctrine_fixture_module в разделе fixturesLoaders описываются используемые загрузчики фикстур. 
В качестве ключа в fixturesLoaders указывается условное имя для загрузчика, а в качестве значения – массив, каждый элемент
которого описывает отдельный загрузчик фикстур. Для каждого элемента в разделе fixturesLoaders создается загрузчик
\Doctrine\Fixture\Loader\ChainLoader и в него добавляются все описанные элементы.

```php

use Doctrine\Fixture\Loader\ClassLoader;

return [
    'nnx_doctrine_fixture_module' => [
        // В секции fixturesLoaders описываются загрузчики фикстур
        'fixturesLoaders' => [
            //Имя цепочки загрузчиков фикстур.
            'test' => [
                //Имя загрузчика фикстур. Может быть произвольным. Нужно только для возможности переопределить конфиг в другом модуле
                'kladr' => [
                    //Плагин из \Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface
                    'name' => ClassLoader::class,
                    //Настройки для загрузчика фикстур
                    'options' => [
                        'classList' => [
                            TestModule1\FooFixture::class,
                        ]
                    ]
                ],
                //Имя загрузчика фикстур. Может быть произвольным. Нужно только для возможности переопределить конфиг в другом модуле
                'okpd' => [
                    //Плагин из \Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface
                    'name' => ClassLoader::class,
                    //Настройки для загрузчика фикстур
                    'options' => [
                        'classList' => [
                            TestModule1\BarFixture::class,
                        ]
                    ]
                ]
            ]            
        ]
    ]
];

```

В приведенном выше примере создается загрузчик фикстур 'test'. Это загрузчик \Doctrine\Fixture\Loader\ChainLoader.
В него добавляются два загрузчика фикстур Doctrine\Fixture\Loader\ClassLoader. Имена kladr и okpd – произвольные. Эти имена
не являются обязательными и служат лишь для того, чтобы оставить возможность перенастроить фикстуры в другом модуле.

Также можно указывать имена фикстур, зарегестрированных в секции fixturesLoaders. Например:


```php

use Doctrine\Fixture\Loader\ClassLoader;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1;

return [
    'nnx_doctrine_fixture_module' => [
        // В секции fixturesLoaders описываются загрузчики фикстур
        'fixturesLoaders' => [
            //Имя цепочки загрузчиков фикстур.
            'test'      => [
                //Имя загрузчика фикстур. Может быть произвольным. Нужно только для возможности переопределить конфиг в другом модуле
                'kladr'              => [
                    //Плагин из \Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface
                    'name'    => ClassLoader::class,
                    //Настройки для загрузчика фикстур
                    'options' => [
                        'classList' => [
                            TestModule1\FooFixture::class,
                        ]
                    ]
                ],
                //Имя загрузчика фикстур. Может быть произвольным. Нужно только для возможности переопределить конфиг в другом модуле
                'childFixtureLoader' => [
                    //Указывается загрузчик фикстур зарегистрированный в fixturesLoaders
                    'name' => 'testChild',
                ]
            ],
            //Имя цепочки загрузчиков фикстур.
            'testChild' => [
                'testItem' => [
                    //Плагин из \Nnx\DoctrineFixtureModule\Loader\FixtureLoaderManagerInterface
                    'name'    => ClassLoader::class,
                    //Настройки для загрузчика фикстур
                    'options' => [
                        'classList' => [
                            TestModule1\BarFixture::class,
                        ]
                    ]
                ],
            ]
        ]
    ]
];

```
