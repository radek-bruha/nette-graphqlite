<?php declare(strict_types=1);

namespace Tests\app\Router;

use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;
use Nette\StaticClass;

/**
 * Class RouterFactory
 *
 * @package Tests\app\Router
 */
final class RouterFactory
{

    use StaticClass;

    /**
     * @return Router
     */
    public static function createRouter(): Router
    {
        return (new RouteList())->addRoute('/graphql', 'GraphQLite:GraphQLite:process');
    }

}