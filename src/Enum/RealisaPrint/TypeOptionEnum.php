<?php


namespace App\Enum\RealisaPrint;
enum TypeOptionEnum: int
{
    case TYPE_OPTION_READONLY = 2;
    case TYPE_OPTION_CHECKBOX = 3;
    case TYPE_OPTION_SELECT = 0;
    case TYPE_OPTION_TEXT = 1;
}