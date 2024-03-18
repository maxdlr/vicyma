<?php

namespace App\Bakery;

use App\Entity\File;
use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;
use Generator;

#[AsBakery(bakes: File::class)]
class FileBakery extends AbstractBakery
{
    public function build(): Generator
    {
        yield [
            'fileName' => $this->faker->word(),
            'fileSize' => $this->faker->randomFloat(2, 2, 30),
            'createdOn' => $this->faker->dateTimeBetween('- 5 days'),
        ];
    }
}