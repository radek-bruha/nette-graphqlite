<?php declare(strict_types=1);

namespace Tests;

use Nette\DI\Container;
use PHPUnit\Framework\TestCase;
use Tests\app\Bootstrap;

/**
 * Class AbstractContainerTestCase
 *
 * @package Tests
 */
abstract class AbstractContainerTestCase extends TestCase
{

    /**
     * @var Container
     */
    protected $container;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = Bootstrap::boot()->createContainer();
    }

}