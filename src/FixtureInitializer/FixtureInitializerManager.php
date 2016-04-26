<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

/**
 * Class FixtureInitializerManager
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class FixtureInitializerManager extends AbstractPluginManager implements FixtureInitializerManagerInterface
{
    /**
     * Имя секции в конфиги приложения отвечающей за настройки менеджера
     *
     * @var string
     */
    const CONFIG_KEY = 'nnx_fixture_initializer';

    /**
     * @inheritDoc
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        $this->setShareByDefault(false);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception\RuntimeException
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof FixtureInitializerInterface) {
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            FixtureInitializerInterface::class
        ));
    }
}
