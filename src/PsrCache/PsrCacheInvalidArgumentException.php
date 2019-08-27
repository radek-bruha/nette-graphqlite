<?php declare(strict_types=1);

namespace Bruha\NetteGraphQLite\PsrCache;

use Exception;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class PsrCacheInvalidArgumentException
 *
 * @package Bruha\NetteGraphQLite\PsrCache
 */
final class PsrCacheInvalidArgumentException extends Exception implements InvalidArgumentException
{

}