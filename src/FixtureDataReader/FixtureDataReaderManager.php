<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

/**
 * Class FixtureDataReaderManager
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader
 */
class FixtureDataReaderManager extends AbstractPluginManager implements FixtureDataReaderManagerInterface
{

    /**
     * FixtureDataReaderManager constructor.
     *
     * @param ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        $this->factories[SimpleXmlFormat::class] = SimpleXmlFormatFactory::class;

        parent::__construct($configuration);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception\RuntimeException
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof FixtureDataReaderInterface) {
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            FixtureDataReaderInterface::class
        ));
    }
}
