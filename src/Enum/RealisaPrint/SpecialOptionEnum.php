<?php

namespace App\Enum\RealisaPrint;

enum SpecialOptionEnum: int
{
    case SPECIAL_OPTION_STANDARD = 0;
    case SPECIAL_OPTION_QUANTITY = 1;
    case SPECIAL_OPTION_DELAY = 2;
    case SPECIAL_OPTION_DELIVERY_COUNTRY = 3;
    case SPECIAL_OPTION_FORMAT = 6;
    case SPECIAL_OPTION_SUPPORT = 7;
}
