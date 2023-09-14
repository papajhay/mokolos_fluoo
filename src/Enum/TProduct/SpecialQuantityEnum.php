<?php

declare(strict_types=1);

namespace App\Enum\TProduct;

enum SpecialQuantityEnum: int
{
    case ID_SPECIAL_QUANTITY_ONLY_STANDARD = 0;
    case ID_SPECIAL_QUANTITY_IN_OPTION = 3;
}
