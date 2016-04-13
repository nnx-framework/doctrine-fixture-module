<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver;

/**
 * Interface EntryNameResolverInterface
 *
 * @package Nnx\EntryNameResolver\EntryNameResolver
 */
interface EntryNameResolverInterface
{
    /**
     * По имени "сервиса" и контексту вызова, определяет имя сервиса специфичное для данного контекста
     *
     * @param      $entryName
     * @param null $context
     *
     * @return string|null
     */
    public function resolveEntryNameByContext($entryName, $context = null);
}
