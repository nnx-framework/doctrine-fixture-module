<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver\Options;

/**
 * Interface ModuleOptionsInterface
 *
 * @package Nnx\EntryNameResolver\Options
 */
interface ModuleOptionsInterface
{
    /**
     * Возвращает список резолверов для определения имени "сервиса", исходя из контекста.
     *
     * @return array
     */
    public function getEntryNameResolvers();
}
