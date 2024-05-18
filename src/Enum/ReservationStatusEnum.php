<?php

namespace App\Enum;

enum ReservationStatusEnum: string
{
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
    case PAID = 'PAID';
    case ARCHIVED = 'ARCHIVED';
    case DELETED = 'DELETED';
}
