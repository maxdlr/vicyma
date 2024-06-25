<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\BedType;
use App\Form\BedTypeType;

#[CrudSetting(entity: BedType::class, formType: BedTypeType::class)]
class BedTypeCrud extends AbstractCrud
{}