<?php

namespace App\DataFixtures\Factory\Config;

use App\Service\ClassBrowser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Exception;

abstract class Factory implements FactoryInterface
{
    protected object $item;

    public function generate(): object
    {
        return $this->item;
    }

    public function persist(ObjectManager $manager): void
    {
        $this->distribute(fn(object $item) => $manager->persist($item));
    }

    public function withCriteria(array $criteria): self
    {
        $this->distribute(/**
         * @throws Exception
         */ fn(object $item) => $this->applyCriteria($criteria));

        return $this;
    }

    public function make(int $number = 1): self
    {
        if ($number === 1) {
            $this->item = $this->build();
        } else {
            $tempCollection = new ArrayCollection();
            for ($i = 0; $i < $number; $i++) {
                $tempCollection->add($this->build());
            }
            $this->item = $tempCollection;
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    protected function applyCriteria(array $criteria): void
    {
        $entityClass = get_class($this->item);

        $properties = ClassBrowser::findAllProperties($entityClass);

        $choices = implode(', ', $properties);

        foreach ($criteria as $field => $value) {
            if (!in_array($field, $properties))
                throw new Exception('"' . $field . '" does not exist.' . PHP_EOL . 'Choices are ' . $choices . '.');

            $setter = ClassBrowser::findSetter($entityClass, $field);
            $this->item->$setter($value);
        }
    }

    protected function isMany(): bool
    {
        return $this->item instanceof ArrayCollection;
    }

    protected function distribute(callable $fn): void
    {
        if ($this->isMany()) {
            foreach ($this->item as $item) {
                $fn($item);
            }
        } else {
            $fn($this->item);
        }
    }
}