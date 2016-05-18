<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Doctrine\Fixture\Event\FixtureEvent;
use Nnx\DoctrineFixtureModule\ResourceLoader\ResourceLoaderServiceInterface;


/**
 * Class ObjectManagerNameInitializer
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class ResourceLoaderInitializer extends AbstractContextInitializer
{
    /**
     * Сервис отвечающий за загрузку ресурсов для фикстуры
     *
     * @var ResourceLoaderServiceInterface
     */
    protected $resourceLoaderService;

    /**
     * ResourceLoaderInitializer constructor.
     *
     * @param ResourceLoaderServiceInterface $resourceLoaderService
     */
    public function __construct(ResourceLoaderServiceInterface $resourceLoaderService)
    {
        $this->setResourceLoaderService($resourceLoaderService);
    }

    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    public function purge(FixtureEvent $event)
    {
    }

    /**
     * {@inheritdoc}
     * @throws \Nnx\DoctrineFixtureModule\FixtureInitializer\Exception\RuntimeException
     */
    public function import(FixtureEvent $event)
    {
    }

    /**
     * Возвращает сервис отвечающий за загрузку ресурсов для фикстуры
     *
     * @return ResourceLoaderServiceInterface
     */
    public function getResourceLoaderService()
    {
        return $this->resourceLoaderService;
    }

    /**
     * Устанавливает сервис отвечающий за загрузку ресурсов для фикстуры
     *
     * @param ResourceLoaderServiceInterface $resourceLoaderService
     *
     * @return $this
     */
    public function setResourceLoaderService(ResourceLoaderServiceInterface $resourceLoaderService)
    {
        $this->resourceLoaderService = $resourceLoaderService;

        return $this;
    }
}
