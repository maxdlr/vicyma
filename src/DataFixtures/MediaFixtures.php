<?php

namespace App\DataFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class MediaFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $lodgingPhotoDir = 'assets/media/images/lodgings';
        $lodgingPhotos = array_diff(scandir($lodgingPhotoDir), ['.', '..']);

        $photos = [];
        foreach ($lodgingPhotos as $floorDir) {
            $photoPath = $lodgingPhotoDir . '/' . $floorDir;
            foreach (array_diff(scandir($photoPath), ['.', '..']) as $photo) {
                $photoPath = str_replace('assets/', '', $photoPath);
                $photos[] = $photoPath . '/' . $photo;
            }
        }

        for ($i = 0; $i < count($photos); $i++) {
            $file = new Media();

            $file
                ->setMediaPath($photos[$i])
                ->setMediaSize(filesize('assets/' . $photos[$i]))
                ->setCreatedOn($faker->dateTimeBetween('- 5 days'));

            $this->setReference('file_' . $i, $file);
            $manager->persist($file);
        }

        $manager->flush();
    }
}
