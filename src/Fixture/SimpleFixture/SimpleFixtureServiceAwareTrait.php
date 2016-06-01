<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Fixture\SimpleFixture;

/**
 * Interface SimpleFixtureServiceInterface
 *
 * @package Nnx\DoctrineFixtureModule\Fixture\SimpleFixture
 */
trait SimpleFixtureServiceAwareTrait
{
    /**
     * Сервис для работы с SimpleFixture
     *
     * @var SimpleFixtureServiceInterface
     */
    protected $simpleFixtureService;

    /**
     * Устанавливает сервис SimpleFixture
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
     * Возвращает сервис SimpleFixture
     *
     * @return SimpleFixtureServiceInterface
     */
    public function getSimpleFixtureService()
    {
        return $this->simpleFixtureService;
    }
}
