<?php

namespace App\DataFixtures\Factory;

use App\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory as Faker;

class FileFactory extends Factory
{
    public function __construct()
    {
        $this->item = new File();
    }

    public function persist(ObjectManager $manager): void
    {
        $this->distribute(fn(File $file) => $manager->persist($file));
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
         */ fn(File $file) => $this->applyCriteria($criteria));

        return $this;
    }

    private function build(): File
    {
        $faker = Faker::create();
        $file = new File();

        $file
            ->setFileName($faker->word())
            ->setFileSize($faker->randomFloat(2, 2, 30))
            ->setCreatedOn($faker->dateTimeBetween('- 5 days'));

        return $file;
    }
}