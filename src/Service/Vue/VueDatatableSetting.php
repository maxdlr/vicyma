<?php

namespace App\Service\Vue;

class VueDatatableSetting
{
    public function __construct(
        public string $name,
        public mixed $default,
        public array $values,
        public string $codeName
    )
    {
    }

    public function getSetting(string $key = null): array|string
    {
        $completeSetting = [
            'name' => $this->name,
            'default' => $this->default,
            'values' => $this->values,
            'codeName' => $this->codeName

        ];

        return match ($key) {
            'name' => $this->name,
            'default' => $this->default,
            'values' => $this->values,
            'codeName' => $this->codeName,
            default => $completeSetting
        };
    }
}