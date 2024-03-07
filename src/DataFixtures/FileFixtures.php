<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\FileFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fileFactory = new FileFactory();
        $files = $fileFactory->make(AppFixtures::FILE_COUNT)->generate();

        $i = 1;
        foreach ($files as $file) {
            $this->setReference('file_' . $i, $file);
            $manager->persist($file);
            $i++;
        }

        $manager->flush();
    }
}
