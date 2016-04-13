<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver;

use Countable;
use Zend\Stdlib\PriorityQueue;

/**
 * Class EntryNameResolver
 *
 * @package Nnx\EntryNameResolver\EntryNameResolver
 */
class EntryNameResolverChain implements Countable, EntryNameResolverInterface
{
    /**
     * Приоритет по умолчанию для резолвера, в момент его добавления  в цепочку
     */
    const DEFAULT_PRIORITY = 1;

    /**
     * Цепочка резолверов
     *
     * @var PriorityQueue
     */
    protected $resolvers;

    /**
     * EntryNameResolver constructor.
     */
    public function __construct()
    {
        $this->resolvers = new PriorityQueue();
    }

    /**
     * Возвращает колличество добавленных резолверов
     *
     * @return int
     */
    public function count()
    {
        return count($this->resolvers);
    }


    /**
     * Добавляет резолвер
     *
     * @param EntryNameResolverInterface $resolver
     * @param int                        $priority
     *
     * @return $this
     */
    public function attach(EntryNameResolverInterface $resolver, $priority = self::DEFAULT_PRIORITY)
    {
        $this->resolvers->insert(
            [
                'instance' => $resolver,

            ],
            $priority
        );

        return $this;
    }


    /**
     * Добавляет резолвер в начало цепочки
     *
     * @param EntryNameResolverInterface $resolver
     *
     * @return $this
     *
     */
    public function prependResolver(EntryNameResolverInterface $resolver)
    {
        $priority = self::DEFAULT_PRIORITY;

        if (!$this->resolvers->isEmpty()) {
            $queue = $this->resolvers->getIterator();
            $queue->setExtractFlags(PriorityQueue::EXTR_PRIORITY);
            $extractedNode = $queue->extract();
            $priority = $extractedNode[0] + 1;
        }

        $this->resolvers->insert(
            [
                'instance' => $resolver,

            ],
            $priority
        );

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param      $entryName
     * @param null $context
     *
     * @return mixed
     */
    public function resolveEntryNameByContext($entryName, $context = null)
    {
    }
}
