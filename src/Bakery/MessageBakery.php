<?php

namespace App\Bakery;

use App\Entity\Message;
use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;

#[AsBakery(bakes: Message::class)]
class MessageBakery extends AbstractBakery
{
    public function build(): \Generator
    {
        yield [
            'subject' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'sentOn' => $this->faker->dateTimeBetween('-2 months'),
        ];
    }
}