<?php

namespace App\DataFixtures\Factory;

use App\Service\ClassBrowser;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class Factory
{
    protected object $item;

    public function generate(): object
    {
        return $this->item;
    }

    /**
     * @throws Exception
     */
    protected function applyCriteria(array $criteria): void
    {
        $properties = ClassBrowser::findAllProperties(get_class($this));

        $choices = implode(', ', $properties);

        foreach ($criteria as $field => $value) {
            if (!in_array($field, $properties))
                throw new Exception('"' . $field . '" does not exist.' . PHP_EOL . 'Choices are ' . $choices . '.');

            $setter = ClassBrowser::findSetter(get_class($this), $field);
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
            $fn();
        }
    }
}