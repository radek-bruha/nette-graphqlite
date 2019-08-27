<?php declare(strict_types=1);

namespace Tests;

use LogicException;
use ReflectionObject;

/**
 * Trait PrivateTrait
 *
 * @package Tests
 */
trait PrivateTrait
{

    /**
     * @param object $object
     * @param string $name
     *
     * @return mixed
     *
     * @throws LogicException
     */
    protected function getProperty(object $object, string $name)
    {
        $reflection = new ReflectionObject($object);

        do {
            if ($reflection->hasProperty($name)) {
                $property = $reflection->getProperty($name);
                $property->setAccessible(TRUE);

                return $property->getValue($object);
            }

            $reflection = $reflection->getParentClass();
        } while ($reflection);

        throw new LogicException(sprintf("Property '%s' Not Found", $name));
    }

    /**
     * @param object $object
     * @param string $name
     * @param mixed  $value
     *
     * @throws LogicException
     */
    protected function setProperty(object $object, string $name, $value): void
    {
        $reflection = new ReflectionObject($object);

        do {
            if ($reflection->hasProperty($name)) {
                $property = $reflection->getProperty($name);
                $property->setAccessible(TRUE);
                $property->setValue($object, $value);

                return;
            }

            $reflection = $reflection->getParentClass();
        } while ($reflection);

        throw new LogicException(sprintf("Property '%s' Not Found", $name));
    }

    /**
     * @param object $object
     * @param string $name
     * @param array  $parameters
     *
     * @return mixed
     *
     * @throws LogicException
     */
    protected function invokeMethod(object $object, string $name, array $parameters = [])
    {
        $reflection = new ReflectionObject($object);

        do {
            if ($reflection->hasMethod($name)) {
                $method = $reflection->getMethod($name);
                $method->setAccessible(TRUE);

                return $method->invokeArgs($object, $parameters);
            }

            $reflection = $reflection->getParentClass();
        } while ($reflection);

        throw new LogicException(sprintf("Method '%s' Not Found", $name));
    }

}