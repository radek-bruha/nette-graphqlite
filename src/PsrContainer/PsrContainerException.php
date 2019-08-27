<?php declare(strict_types=1);

namespace Bruha\NetteGraphQLite\PsrContainer;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class PsrContainerException
 *
 * @package Bruha\NetteGraphQLite\PsrContainer
 */
final class PsrContainerException extends Exception implements ContainerExceptionInterface
{

}