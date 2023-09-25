<?php

declare(strict_types=1);

namespace App\Enum;

enum StatusEnum: int
{
    case STATUS_INACTIVE  = 0;
    case STATUS_ACTIVE = 1;
}