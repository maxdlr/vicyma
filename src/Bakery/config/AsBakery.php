<?php

namespace App\Bakery\config;

use Attribute;

#[Attribute]
class AsBakery
{
    public function __construct(
        public string $bakes
    )
    {
    }
}
