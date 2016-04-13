<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver;

/**
 * Interface EntryNameResolverProviderInterface
 *
 * @package Nnx\EntryNameResolver\EntryNameResolver
 */
interface EntryNameResolverProviderInterface
{
    /**
     * Настройки для EntryNameResolverManager
     *
     * @return array
     */
    public function getEntryNameResolverConfig();
}
