<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Doctrine\Fixture\Event\FixtureEvent;
use Doctrine\Fixture\Fixture;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixture\SimpleFixtureServiceInterface;
use Nnx\DoctrineFixtureModule\Fixture\SimpleFixtureInterface;


/**
 * Class SimpleFixtureServiceInitializer
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class SimpleFixtureInitializer extends AbstractContextInitializer
{

    /**
     * Сервис обеспечивающий работу с SimpleFixture
     *
     * @var SimpleFixtureServiceInterface
     */
    protected $simpleFixtureService;

    /**
     * SimpleFixtureInitializer constructor.
     *
     * @param SimpleFixtureServiceInterface $simpleFixtureService
     */
    public function __construct(SimpleFixtureServiceInterface $simpleFixtureService)
    {
        $this->setSimpleFixtureService($simpleFixtureService);
    }

    /**
     * Возвращает сервис обеспечивающий работу с SimpleFixture
     *
     * @return SimpleFixtureServiceInterface
     */
    public function getSimpleFixtureService()
    {
        return $this->simpleFixtureService;
    }

    /**
     * Устанавливает сервис обеспечивающий работу с SimpleFixture
     *
     * @param SimpleFixtureServiceInterface $simpleFixtureService
     *
     * @return $this
     */
    public function setSimpleFixtureService(SimpleFixtureServiceInterface $simpleFixtureService)
    {
        $this->simpleFixtureService = $simpleFixtureService;

        return $this;
    }


    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function purge(FixtureEvent $event)
    {
        $fixture = $event->getFixture();
        $this->inject($fixture);
    }

    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function import(FixtureEvent $event)
    {
        $fixture = $event->getFixture();
        $this->inject($fixture);
    }

    /**
     * Загрузка ресуров для фикстуры
     *
     * @param Fixture $fixture
     *
     * @return $this
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    protected function inject(Fixture $fixture)
    {
        if ($fixture instanceof SimpleFixtureInterface) {
            $simpleFixtureService = $this->getSimpleFixtureService();
            $fixture->setSimpleFixtureService($simpleFixtureService);
        }

        return $this;
    }
}
