<?php declare(strict_types=1);

namespace Bruha\NetteGraphQLite\PsrContainer;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class PsrContainerNotFoundException
 *
 * @package Bruha\NetteGraphQLite\PsrContainer
 */
final class PsrContainerNotFoundException extends Exception implements NotFoundExceptionInterface
{

}