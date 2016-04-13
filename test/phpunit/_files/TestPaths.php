<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver\PhpUnit\TestData;

/**
 * Class TestPaths
 *
 * @package Nnx\EntryNameResolver\PhpUnit\TestData
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
     * Путь до директории в которой находятся тестовые модули сервиса входящего в приложение, используемого для тестирования
     * определения имени сервиса по контексту
     *
     * @return string
     */
    public static function getPathToContextResolverTestServiceDir()
    {
        return  __DIR__ . '/../_files/ContextResolver/vendor/service/';
    }


    /**
     * Путь до директории в которой находятся тестовые модули сервиса входящего в приложение, используемого для тестирования
     * определения имени сервиса по контексту
     *
     * @return string
     */
    public static function getPathToContextResolverTestCustomServiceDir()
    {
        return  __DIR__ . '/../_files/ContextResolver/vendor/custom-service/';
    }


    /**
     * Путь до файла приложения, используемого для тестирования получения сервиса по контексту
     *
     * @return string
     */
    public static function getPathToContextResolverAppConfig()
    {
        return  __DIR__ . '/../_files/ContextResolver/config/application.config.php';
    }
}
