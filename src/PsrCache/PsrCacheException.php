<?php declare(strict_types=1);

namespace Bruha\NetteGraphQLite\PsrCache;

use Exception;
use Psr\SimpleCache\CacheException;

/**
 * Class PsrCacheException
 *
 * @package Bruha\NetteGraphQLite\PsrCache
 */
final class PsrCacheException extends Exception implements CacheException
{

}