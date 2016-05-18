<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Doctrine\Fixture\Event\FixtureEvent;


/**
 * Class ObjectManagerNameInitializer
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class ObjectManagerNameInitializer extends AbstractContextInitializer
{

    /**
     * Имя параметра из контекста, значение которого содержит имя ObjectManager'a
     *
     * @var string
     */
    const OBJECT_MANAGER_NAME = 'objectManagerName';

    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    public function purge(FixtureEvent $event)
    {
        $this->injected($event);
    }

    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    public function import(FixtureEvent $event)
    {
        $this->injected($event);
    }

    /**
     * Устанавливает зависимости в фикстуру
     *
     * @param FixtureEvent $event
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    protected function injected(FixtureEvent $event)
    {
        $fixture = $event->getFixture();

        if (! ($fixture instanceof ObjectManagerNameAwareInterface)) {
            return;
        }

        $objectManagerName = $this->getContextParam(static::OBJECT_MANAGER_NAME);
        $fixture->setObjectManagerName($objectManagerName);
    }
}
