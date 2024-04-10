<?php

namespace App\Enum;

enum ReservationStatusEnum: string
{
    case CONFIRMED = 'CONFIRMED';
    case ARCHIVED = 'ARCHIVED';
    case DELETED = 'DELETED';
    case PENDING = 'PENDING';
}
