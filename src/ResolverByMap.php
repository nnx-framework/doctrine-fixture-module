<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver;

/**
 * Class ResolverByMap
 *
 * @package Nnx\EntryNameResolver\EntryNameResolver
 */
class ResolverByMap implements EntryNameResolverInterface
{
    /**
     * Карта используемая для определения имени сервиса в зависимости от контекста вызова
     *
     * 'имяСервиса' => [
     *      'имяМодуля' => 'имяСервисаДляЭтогоМодуля'
     * ]
     *
     * @var array
     */
    protected $contextMap = [];

    /**
     * ResolverByMap constructor.
     *
     * @param array $contextMap
     */
    public function __construct(array $contextMap = [])
    {
        $this->setContextMap($contextMap);
    }


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

    /**
     * Возвращает карту используемую для определения имени сервиса в зависимости от контекста вызова
     *
     * @return array
     */
    public function getContextMap()
    {
        return $this->contextMap;
    }

    /**
     * Устанавливает карту используемую для определения имени сервиса в зависимости от контекста вызова
     *
     * @param array $contextMap
     *
     * @return $this
     */
    public function setContextMap($contextMap)
    {
        $this->contextMap = $contextMap;

        return $this;
    }
}
