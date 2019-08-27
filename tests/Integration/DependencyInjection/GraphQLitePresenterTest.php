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
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        Debugger::$productionMode = TRUE;
    }

    /**
     * @covers GraphQLitePresenter::actionProcess
     * @covers GraphQLiteResolver::query
     */
    public function testQuery(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @covers GraphQLitePresenter::actionProcess
     * @covers GraphQLiteResolver::mutation
     */
    public function testMutation(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @covers GraphQLitePresenter::actionProcess
     * @covers GraphQLiteResolver::query
     */
    public function testQueryError(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @covers GraphQLitePresenter::actionProcess
     * @covers GraphQLiteResolver::mutation
     */
    public function testMutationError(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @covers GraphQLitePresenter::actionProcess
     * @covers GraphQLiteResolver::query
     */
    public function testQueryException(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

    /**
     * @covers GraphQLitePresenter::actionProcess
     * @covers GraphQLiteResolver::mutation
     */
    public function testMutationException(): void
    {
        $data = $this->sendRequest($this->getRequestData());
        $this->assertResponse($this->getResponseData($data), $data);
    }

}