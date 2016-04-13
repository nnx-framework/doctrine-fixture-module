<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver;

/**
 * Class EntryNameResolver
 *
 * @package Nnx\EntryNameResolver\EntryNameResolver
 */
class ResolverByClassName implements EntryNameResolverInterface
{
    /**
     * @inheritdoc
     *
     * @param      $entryName
     * @param null $context
     *
     * @return null|string
     */
    public function resolveEntryNameByContext($entryName, $context = null)
    {
    }
}
