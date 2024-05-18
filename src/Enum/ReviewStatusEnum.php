<?php

namespace App\Enum;

enum ReviewStatusEnum: string
{
    case PENDING = 'PENDING';
    case ACCEPTED = 'ACCEPTED';
    case REJECTED = 'REJECTED';
}
