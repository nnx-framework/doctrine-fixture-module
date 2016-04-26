<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Doctrine\Fixture\Persistence\ConnectionRegistryEventSubscriber as ConnectionRegistryEventSubscriberBase;

/**
 * Class ConnectionRegistryEventSubscriber
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class ConnectionRegistryEventSubscriber extends ConnectionRegistryEventSubscriberBase implements FixtureInitializerInterface
{
}
