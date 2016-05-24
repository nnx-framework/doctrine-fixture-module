<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Doctrine\Fixture\Event\ImportFixtureEventListener;
use Doctrine\Fixture\Event\PurgeFixtureEventListener;
use Doctrine\Fixture\Event\FixtureEvent;
use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerAwareInterface;
use Nnx\DoctrineFixtureModule\FixtureDataReader\FixtureDataReaderManagerInterface;

/**
 * Class FixtureDataReaderManageInitializer
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class FixtureDataReaderManageInitializer implements
    FixtureInitializerInterface,
    ImportFixtureEventListener,
    PurgeFixtureEventListener
{

    /**
     * Менеджер для работы с данными для фикстуры
     *
     * @var FixtureDataReaderManagerInterface
     */
    protected $fixtureDataReaderManager;

    /**
     * FixtureDataReaderManageInitializer constructor.
     *
     * @param FixtureDataReaderManagerInterface $fixtureDataReaderManager
     */
    public function __construct(FixtureDataReaderManagerInterface $fixtureDataReaderManager)
    {
        $this->setFixtureDataReaderManager($fixtureDataReaderManager);
    }

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

        if (! ($fixture instanceof FixtureDataReaderManagerAwareInterface)) {
            return;
        }

        $fixtureDataReaderManager = $this->getFixtureDataReaderManager();
        $fixture->setFixtureDataReaderManager($fixtureDataReaderManager);
    }

    /**
     * Возвращает менеджер для работы с данными для фикстуры
     *
     * @return FixtureDataReaderManagerInterface
     */
    public function getFixtureDataReaderManager()
    {
        return $this->fixtureDataReaderManager;
    }

    /**
     * Устанавливает менеджер для работы с данными для фикстуры
     *
     * @param FixtureDataReaderManagerInterface $fixtureDataReaderManager
     *
     * @return $this
     */
    public function setFixtureDataReaderManager(FixtureDataReaderManagerInterface $fixtureDataReaderManager)
    {
        $this->fixtureDataReaderManager = $fixtureDataReaderManager;

        return $this;
    }
}
