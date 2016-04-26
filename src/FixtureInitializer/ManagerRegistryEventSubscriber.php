<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureInitializer;

use Doctrine\Fixture\Persistence\ManagerRegistryEventSubscriber as ManagerRegistryEventSubscriberBase;

/**
 * Class ManagerRegistryEventSubscriber
 *
 * @package Nnx\DoctrineFixtureModule\FixtureInitializer
 */
class ManagerRegistryEventSubscriber extends ManagerRegistryEventSubscriberBase implements FixtureInitializerInterface
{
}
