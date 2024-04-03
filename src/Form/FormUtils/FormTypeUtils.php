<?php

namespace App\Form\FormUtils;

use function Symfony\Component\String\u;

class FormTypeUtils
{
    static public function makeIntChoices(int $number): array
    {
        $choices = [];
        for ($i = 1; $i <= $number; $i++) {
            $iString = u($i)->toString();
            $choices[$iString] = $iString;
        }
        return $choices;
    }
}