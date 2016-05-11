# Фильтры фикстур

Фильтр фикстур - класс имплементирующий \Doctrine\Fixture\Filter\Filter, определяет условия того, какие фикстуры должны быть выполены. 


Для работы с фильтрами фикстур используется специальный менеджер плагинов - \Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface.
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
\Doctrine\Fixture\Filter\ChainFilter               |Позволяет объеденить фильтры в цепочку
\Doctrine\Fixture\Filter\GroupedFilter             |Позволяет запускать фикстуры принадлежащие к определенной группе
\Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture|Обеспечивает возможность не выполнять фикстуры повторно


Каждый из этих фильтро фикстур зарегестрирован в менеджере плагинов \Nnx\DoctrineFixtureModule\Filter\FixtureFilterManagerInterface.

## Фильтр \Doctrine\Fixture\Filter\ChainFilter

Объеденяет фильтры в цепочку


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

При создание экземпляра фильтра фикстур \Doctrine\Fixture\Filter\ChainFilter через плагин менеджер, в опциях с 
помощью параметра filterList, можно указать массив объектов фильтров фикстур.

После вызова метода accept у экземпляра объекта \Doctrine\Fixture\Filter\ChainFilter, у всех фильтров фикстур, образующих
цепочку, будет вызыван аналогичный метод, и произведена проверка, нужно ли выполнять данную фикстуру.

## Фильтр \Doctrine\Fixture\Filter\GroupedFilter

Для работы с группами фикстур, необходимо что бы фикстуры реализовывали интерфейс \Doctrine\Fixture\Filter\GroupdFixteure.
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

При создание экземпляра фильтра фикстур \Doctrine\Fixture\Filter\GroupedFilter через плагин менеджер, в опциях с 
помощью параметра allowedGroupList, можно указать список групп, к которым должна относится фикстура, что бы она была 
выполненна.

Также можно указать параметр onlyImplementors(значение по умолчанию false). Если этот параметр имеет значение true, то
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

Фильтр позволяет для конркетного Executor'a (описанного в конфигах приложения), выполинть фикстуры только один раз. Таким 
образом, повторый запуск такого Executor'a, не приведет к повторному выполнению, ранее выполенных фикстур, будут выполнены только, новые фикстуры.


## Независимое использование фильтра \Nnx\DoctrineFixtureModule\Filter\FilterUsedFixture

Для использования фильтра FilterUsedFixture, при его создание необходимо передать Executor, в контексте которого будет запускаться фикстура.

```php

/** @var \Nnx\DoctrineFixtureModule\Executor\DefaultExecutorConfiguration $configuration */
$configuration = $serviceLocator->get(\Nnx\DoctrineFixtureModule\Executor\DefaultExecutorConfiguration::class);

/** @var \Nnx\DoctrineFixtureModule\Executor\FixtureExecutorBuilderInterface $configuration */
$builder = $serviceLocator->get(\Nnx\DoctrineFixtureModule\Executor\FixtureExecutorBuilderInterface::class);



```

