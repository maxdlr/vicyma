<?php

namespace App\Crud\Manager;

use Attribute;

#[Attribute]
final readonly class CrudSetting
{
    public function __construct(
        public ?string $entity = null,
        public ?string $formType = null,
        public ?string $repository = null,
    )
    {
    }
}