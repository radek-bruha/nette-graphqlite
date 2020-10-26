<?php declare(strict_types=1);

namespace Bruha\NetteGraphQLite\PsrContainer;

use Nette\DI\Container;
use Nette\DI\MissingServiceException;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 * Class PsrContainer
 *
 * @package Bruha\NetteGraphQLite\PsrContainer
 */
final class PsrContainer implements ContainerInterface
{

    /**
     * @var Container
     */
    private Container $container;

    /**
     * PsrContainer constructor
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $service
     *
     * @return mixed
     *
     * @throws PsrContainerException
     * @throws PsrContainerNotFoundException
     */
    public function get($service)
    {
        try {
            return $this->container->getByType($service);
        } catch (MissingServiceException $exception) {
            throw new PsrContainerNotFoundException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (Throwable $throwable) {
            throw new PsrContainerException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }

    /**
     * @param mixed $service
     *
     * @return bool
     */
    public function has($service): bool
    {
        try {
            $this->container->getByType($service);

            return TRUE;
        } catch (Throwable $throwable) {
            return FALSE;
        }
    }

}