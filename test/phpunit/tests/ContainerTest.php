<?php
/**
 * @link    https://github.com/nnx-framework/entry-name-resolver
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\EntryNameResolver\PhpUnit\Test;

use Nnx\EntryNameResolver\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\EntryNameResolver\Module;

/**
 * Class ContainerTest
 *
 * @package Nnx\EntryNameResolver\PhpUnit\Test
 */
class ContainerTest extends AbstractHttpControllerTestCase
{
    /**
     *
     * @return void
     * @throws \Zend\Stdlib\Exception\LogicException
     */
    public function testLoadModule()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToContextResolverAppConfig()
        );

        $this->assertModulesLoaded([Module::MODULE_NAME]);
    }
}
