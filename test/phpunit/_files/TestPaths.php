<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData;

/**
 * Class TestPaths
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData
 */
class TestPaths
{

    /**
     * Путь до директории модуля
     *
     * @return string
     */
    public static function getPathToModule()
    {
        return __DIR__ . '/../../../';
    }

    /**
     * Путь до конфига приложения по умолчанию
     *
     * @return string
     */
    public static function getPathToDefaultAppConfig()
    {
        return  __DIR__ . '/../_files/DefaultApp/application.config.php';
    }

    /**
     * Путь до файла приложения, используемого для тестирования получения сервиса по контексту
     *
     * @return string
     */
    public static function getPathToFixtureTestAppConfig()
    {
        return  __DIR__ . '/../_files/FixtureTestApp/config/application.config.php';
    }
}
