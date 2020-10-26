<?php declare(strict_types=1);

namespace Tests\Integration\DependencyInjection;

use Tests\AbstractResolverTestCase;
use Tracy\Debugger;

/**
 * Class GraphQLitePresenterTest
 *
 * @package Tests\Integration\DependencyInjection
 */
final class GraphQLitePresenterTest extends AbstractResolverTestCase
{

    /**
     * @coversNothing
     */
    public function testQuery(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @coversNothing
     */
    public function testMutation(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @coversNothing
     */
    public function testQueryError(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @coversNothing
     */
    public function testMutationError(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @coversNothing
     */
    public function testQueryException(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @coversNothing
     */
    public function testMutationException(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        Debugger::$productionMode = TRUE;
    }

}