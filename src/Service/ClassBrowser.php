<?php

namespace App\Service;

use Exception;
use ReflectionClass;
use ReflectionException;

class ClassBrowser
{
    /**
     * @throws ReflectionException
     */
    static public function findSetter(
        string $className,
        string $needle
    ): string
    {
        return self::methodBrowser($className, $needle, 'set');
    }

    /**
     * @throws ReflectionException
     */
    static public function findGetter(
        string $className,
        string $needle
    ): string
    {
        return self::methodBrowser($className, $needle, 'get');
    }

    /**
     * @throws ReflectionException
     */
    static private function methodBrowser(
        string $className,
        string $needle,
        string $methodPrefix,
    ): ?string
    {
        $reflection = new ReflectionClass($className);

        try {
            foreach ($reflection->getMethods() as $method) {
                if (strchr($method->name, $methodPrefix . $needle)) {
                    return $method->name;
                }
            }
        } catch (Exception $exception) {
            dd($exception);
        }
        return null;
    }
}