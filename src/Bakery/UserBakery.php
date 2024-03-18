<?php

namespace App\Bakery;

use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;
use App\Entity\User;
use Generator;

#[AsBakery(bakes: User::class)]
final class UserBakery extends AbstractBakery
{
    public function build(): Generator
    {
        yield [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phoneNumber' => $this->faker->phoneNumber(),
            'roles' => [$this->faker->word()],
            'password' => $this->faker->password()
        ];
    }
}

