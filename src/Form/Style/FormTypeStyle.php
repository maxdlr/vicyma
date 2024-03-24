<?php

namespace App\Form\Style;

use App\Enum\FormTypeEnum\FormTypeFieldClassEnum;
use App\Enum\FormTypeEnum\FormTypeLabelClassEnum;
use App\Enum\FormTypeEnum\FormTypeRowClassEnum;

class FormTypeStyle
{
    static private string $fieldClass;
    static private string $labelClass;
    static private string $rowClass;

    public function __construct()
    {
        self::$fieldClass = implode(' ', [
            FormTypeFieldClassEnum::PY->value,
            FormTypeFieldClassEnum::PS->value,
            FormTypeFieldClassEnum::ROUNDED->value,
            FormTypeFieldClassEnum::FLEX_COLUMN->value
        ]);

        self::$labelClass = implode(' ', [
            FormTypeLabelClassEnum::PY->value
        ]);

        self::$rowClass = implode(' ', [
            FormTypeRowClassEnum::PY->value
        ]);
    }

    static public function textTypeField(string $label, string $placeholder): array
    {
        return [
            'row_attr' => [
                'class' => self::$rowClass
            ],
            'label_attr' => [
                'class' => self::$labelClass
            ],
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
                'class' => self::$fieldClass
            ]
        ];
    }

    static public function integerTypeField(string $label, string $placeholder): array
    {
        return [
            'label' => $label,
            'placeholder' => $placeholder
        ];
    }
}