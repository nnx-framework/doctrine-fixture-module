# Загрузка ресурсов для фикстуры

Часто для загрузки данныз в db, используются внешние ресурсы. Это могут xml файлы с данными, csv файлы и т.д.
Подготовка данных для фикстур (например скопировать их в директорию, с которой работает фикстура) - задача для загрузчика
ресурсов.

С помощью конфигов на уровне приложения, можно указать, какой загрузчик фикстур, и с какими параметрами, будет вызван
для заданной фикстуры.
 
 
Бысрый старт

- Создать класс загрузчика фикстуры
    - В примере неже, загрузчик копирует файл для фикстуры, в заданную дирикторую
    - Для получения данных из фикстуры, рекомендуется применять интерфейсы (в примере это TargetDirInterface)

```php

namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\ResourceLoader;

use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderInterface;

/**
 * Class MyFixtureResourceLoader
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\ResourceLoader
 */
class MyFixtureResourceLoader implements ResourceLoaderInterface
{
    /**
     * Путь до файла с данными
     *
     * @var string
     */
    protected $sourceFile;

    /**
     * @inheritDoc
     */
    public function loadResourceForFixture(Fixture $fixture)
    {
        if ($fixture instanceof TargetDirInterface) {
            $targetDir = $fixture->getTargetDir();

            copy($this->sourceFile, $targetDir . DIRECTORY_SEPARATOR . basename($this->sourceFile));

        }
    }

    /**
     * @return string
     */
    public function getSourceFile()
    {
        return $this->sourceFile;
    }

    /**
     * @param string $sourceFile
     *
     * @return $this
     */
    public function setSourceFile($sourceFile)
    {
        $this->sourceFile = $sourceFile;

        return $this;
    }


}


```

- Создать фабрику для загрузчика ресурсов

```php
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\ResourceLoader;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MyFixtureResourceLoaderFactory
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\ResourceLoader
 */
class MyFixtureResourceLoaderFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $creationOptions = $this->getCreationOptions();
        $sourceFile = $creationOptions['sourceFile'];
        $resourceLoader = new MyFixtureResourceLoader();

        $resourceLoader->setSourceFile($sourceFile);

        return $resourceLoader;
    }

}

```

- Зарегестрировать загрузчик ресурсов для фикстур в менеджере плагинов \Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderManagerInterface
    - Проще всего регистрировать через конфиги приложения
    
```php


use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderManager;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\ResourceLoader;

return [
    ResourceLoaderManager::CONFIG_KEY => [
        'factories'          => [
            ResourceLoader\MyFixtureResourceLoader::class => ResourceLoader\MyFixtureResourceLoaderFactory::class

        ]
    ],
];


```

- Указать для какой фикстуры, необходимо вызывать загрузчик ресуров, а также указать параметры для загрузчика ресурсов

```php

<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule;

use Nnx\DoctrineFixtureModule\Module as DoctrineFixtureModule;
use Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1\ResourceLoader;

return [
    DoctrineFixtureModule::CONFIG_KEY => [
        'resourceLoader'     => [
            MyFixture::class => [
                'name' => ResourceLoader\MyFixtureResourceLoader::class,
                'options' => [
                    'sourceFile' => __DIR__ . '/../data/sourceFile.xml'
                ]
            ]
        ],

    ]
];

```


Теперь при запуске фикстуры MyFixture, сначала отработает соответствующий загрузчик фикстур