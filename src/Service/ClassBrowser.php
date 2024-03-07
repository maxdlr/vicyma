<?php

namespace App\Service;

use Exception;
use ReflectionClass;
use ReflectionException;
use function Symfony\Component\String\u;

class ClassBrowser
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    static public function findAllProperties(string $classFQCN): array
    {
        $reflection = new ReflectionClass($classFQCN);
        $properties = [];

        try {
            foreach ($reflection->getProperties() as $property) {
                $properties[] = $property->name;
            }
            return $properties;
        } catch (Exception) {
            throw new Exception('Cannot find ' . $needle . ' in ' . $classFQCN . '.');
        }
    }

    /**
     * @throws ReflectionException
     */
    static public function findSetter(
        string $classFQCN,
        string $needle
    ): string
    {
        return self::methodBrowser($classFQCN, $needle, 'set');
    }

    /**
     * @throws ReflectionException
     */
    static public function findGetter(
        string $classFQCN,
        string $needle
    ): string
    {
        return self::methodBrowser($classFQCN, $needle, 'get');
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    static private function methodBrowser(
        string $classFQCN,
        string $needle,
        string $methodPrefix,
    ): ?string
    {
        $reflection = new ReflectionClass($classFQCN);
        $needle = u($needle)->title()->toString();

        try {
            foreach ($reflection->getMethods() as $method) {
                if (strchr($method->name, $methodPrefix . $needle)) {
                    return $method->name;
                }
            }
        } catch (Exception) {
            throw new Exception('Cannot find ' . $needle . ' in ' . $classFQCN . '.');
        }
        return null;
    }
}