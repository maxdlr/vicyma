<?php

namespace App\DataFixtures\Factory;

use App\Entity\Bed;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Faker\Factory as Faker;

class BedFactory extends Factory
{
    public function __construct()
    {
        $this->item = new Bed();
    }

    public function make(int $number = 1): self
    {
        if ($number === 1) {
            $this->item = self::build();
        } else {
            $tempCollection = new ArrayCollection();
            for ($i = 0; $i < $number; $i++) {
                $tempCollection->add(self::build());
            }
            $this->item = $tempCollection;
        }
        return $this;
    }

    public function withCriteria(array $criteria): self
    {
        $this->distribute(/**
         * @throws Exception
         */ fn(Bed $bed) => $this->applyCriteria($criteria));

        return $this;
    }

    private function build(): Bed
    {
        $faker = Faker::create();
        $bed = new Bed();

        $bed
            ->setHeight($faker->randomElement([190, 180, 200]))
            ->setWidth($faker->randomElement([140, 90, 180, 200]))
            ->setIsExtra($faker->boolean());

        return $bed;
    }
}