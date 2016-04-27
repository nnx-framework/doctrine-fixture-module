<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Doctrine\Fixture\Event\FixtureEvent;
use Doctrine\Fixture\Event\ImportFixtureEventListener;
use Doctrine\Fixture\Event\PurgeFixtureEventListener;

/**
 * Class ObjectManagerNameInitializer
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
abstract class AbstractContextInitializer
    implements
        FixtureInitializerInterface,
        ImportFixtureEventListener,
        PurgeFixtureEventListener
{

    /**
     * Данные контекста
     *
     * @var array|null
     */
    protected $contextData;

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            ImportFixtureEventListener::IMPORT,
            PurgeFixtureEventListener::PURGE,
        ];
    }

    /**
     * {@inheritdoc}
     */
    abstract public function purge(FixtureEvent $event);

    /**
     * {@inheritdoc}
     */
    abstract public function import(FixtureEvent $event);

    /**
     * Возвращает данные контекста
     *
     * @return array
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    public function getContextData()
    {
        if (null === $this->contextData) {
            $errMsg = 'Context not specified';
            throw new Exception\RuntimeException($errMsg);
        }
        return $this->contextData;
    }

    /**
     * Устанавливает данные контекста
     *
     * @param array|null $contextData
     *
     * @return $this
     */
    public function setContextData(array $contextData)
    {
        $this->contextData = $contextData;

        return $this;
    }

    /**
     * Возвращает значение параметра из контекста
     *
     * @param $name
     *
     * @return mixed
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    public function getContextParam($name)
    {
        $context = $this->getContextData();
        if (!array_key_exists($name, $context)) {
            $errMsg = sprintf('Param %s not found in context', $name);
            throw new Exception\RuntimeException($errMsg);
        }

        return $context[$name];
    }
}
