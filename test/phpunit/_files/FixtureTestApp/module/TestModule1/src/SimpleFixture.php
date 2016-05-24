<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1;

use Nnx\DoctrineFixtureModule\Fixture\AbstractSimpleFixture;

/**
 * Class SimpleFixture
 *
 * @package Nnx\DoctrineFixtureModule\PhpUnit\TestData\FixtureTestApp\TestModule1
 */
class SimpleFixture extends AbstractSimpleFixture
{
    /**
     * SimpleFixture constructor.
     */
    public function __construct()
    {
        $this->setResourceFixtureDir(__DIR__ . '/../dataFixture/simpleFixtureTest');
    }
}