<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class FixtureFilterManager
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
class FixtureExecutorManager extends AbstractPluginManager implements FixtureExecutorManagerInterface
{
    /**
     * Имя секции в конфиги приложения отвечающей за настройки менеджера
     *
     * @var string
     */
    const CONFIG_KEY = 'nnx_executor_filter';

    /**
     * {@inheritDoc}
     *
     * @throws Exception\RuntimeException
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof ExecutorInterface) {
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            ExecutorInterface::class
        ));
    }
}
