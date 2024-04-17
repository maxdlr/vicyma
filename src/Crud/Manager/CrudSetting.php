<?php

namespace App\Crud\Manager;

use Attribute;

/**
 * @author Maxime de la Rocheterie
 */
#[Attribute]
final readonly class CrudSetting
{
    /**
     * Both $entity and $formType need to be Fully Qualified Class Names
     *
     * @param string|null $entity
     * @param string|null $formType
     */
    public function __construct(
        public ?string $entity = null,
        public ?string $formType = null,
    )
    {
    }
}