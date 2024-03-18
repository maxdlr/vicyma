<?php

namespace App\DataFixtures;

use App\Bakery\FileBakery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use ReflectionException;

class FileFixtures extends Fixture
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $fileFactory = new FileBakery();
        $files = $fileFactory->makeMany(AppFixtures::FILE_COUNT)->bake();

        $i = 1;
        foreach ($files as $file) {
            $this->setReference('file_' . $i, $file);
            $manager->persist($file);
            $i++;
        }

        $manager->flush();
    }
}
