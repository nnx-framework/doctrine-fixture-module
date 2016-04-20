<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

/**
 * Class ExecutorAwareTrait
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
trait ExecutorAwareTrait
{
    /**
     * Executor для фикстур
     *
     * @var ExecutorInterface
     */
    protected $executor;

    /**
     * @inheritdoc
     *
     * @return ExecutorInterface
     *
     * @throws Exception\RuntimeException
     */
    public function getExecutor()
    {
        if (null === $this->executor) {
            $errMsg = 'Fixture executor not found';
            throw new Exception\RuntimeException($errMsg);
        }
        return $this->executor;
    }

    /**
     * @inheritdoc
     *
     * @param ExecutorInterface $executor
     *
     * @return $this
     */
    public function setExecutor(ExecutorInterface $executor)
    {
        $this->executor = $executor;

        return $this;
    }
}
