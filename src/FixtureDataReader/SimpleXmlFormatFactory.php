<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SimpleXmlFormatFactory
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader
 */
class SimpleXmlFormatFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     *
     * @return SimpleXmlFormat
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SimpleXmlFormat();
    }
}
