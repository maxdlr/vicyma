<?php

namespace App\DataFixtures\Factory\Config;

use App\Service\ClassBrowser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Exception;

abstract class Factory implements FactoryInterface
{
    protected static object $item;

    public static function generate(): object
    {
        return static::$item;
    }

    public static function persist(ObjectManager $manager): void
    {
        static::distribute(fn(object $item) => $manager->persist($item));
    }

    public static function withCriteria(array $criteria): static
    {
        static::distribute(/**
         * @throws Exception
         */ fn(object $item) => static::applyCriteria($criteria));

        return new static;
    }

    public static function make(int $number = 1): static
    {
        if ($number === 1) {
            static::$item = static::build();
        } else {
            $tempCollection = new ArrayCollection();
            for ($i = 0; $i < $number; $i++) {
                $tempCollection->add(static::build());
            }
            static::$item = $tempCollection;
        }
        return new static;
    }

    /**
     * @throws Exception
     */
    protected static function applyCriteria(array $criteria): void
    {
        $entityClass = get_class(static::$item);

        $properties = ClassBrowser::findAllProperties($entityClass);

        $choices = implode(', ', $properties);

        foreach ($criteria as $field => $value) {
            if (!in_array($field, $properties))
                throw new Exception('"' . $field . '" does not exist.' . PHP_EOL . 'Choices are ' . $choices . '.');

            $setter = ClassBrowser::findSetter($entityClass, $field);
            static::$item->$setter($value);
        }
    }

    protected static function isMany(): bool
    {
        return static::$item instanceof ArrayCollection;
    }

    protected static function distribute(callable $fn): void
    {
        if (static::isMany()) {
            foreach (static::$item as $item) {
                $fn($item);
            }
        } else {
            $fn(static::$item);
        }
    }
}