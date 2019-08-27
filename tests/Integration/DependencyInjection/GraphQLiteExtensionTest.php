<?php declare(strict_types=1);

namespace Tests\Integration\DependencyInjection;

use Nette\DI\Container;
use Tests\AbstractContainerTestCase;

/**
 * Class GraphQLiteExtensionTest
 *
 * @package Tests\Integration\DependencyInjection
 */
final class GraphQLiteExtensionTest extends AbstractContainerTestCase
{

    /**
     * @covers GraphQLiteExtension::getConfigSchema
     * @covers GraphQLiteExtension::beforeCompile
     */
    public function testExtension(): void
    {
        self::assertTrue(is_a($this->container, Container::class, TRUE));
    }

}