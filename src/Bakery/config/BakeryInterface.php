<?php

namespace App\Bakery\config;

use Generator;

interface BakeryInterface
{
    public function build(): Generator;
}
