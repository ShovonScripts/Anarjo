<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Failed = 'failed';
}
