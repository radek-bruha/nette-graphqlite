<?php declare(strict_types=1);

namespace Tests\app\Resolver;

use LogicException;
use TheCodingMachine\GraphQLite\Annotations as GraphQL;

/**
 * Class GraphQLiteResolver
 *
 * @package Tests\app\Resolver
 */
final class GraphQLiteResolver
{

    /**
     * @GraphQL\Query()
     *
     * @param string $name
     *
     * @return string
     *
     * @throws LogicException
     */
    public function query(string $name): string
    {
        if ($name === 'NotUnknown') {
            throw new LogicException('This name is not allowed!');
        }

        return sprintf('Hello, %s', $name);
    }

    /**
     * @GraphQL\Mutation()
     *
     * @param string $name
     *
     * @return string
     *
     * @throws LogicException
     */
    public function mutation(string $name): string
    {
        if ($name === 'NotUnknown') {
            throw new LogicException('This name is not allowed!');
        }

        return sprintf('Hello, %s', $name);
    }

}