<?php

namespace App\Helpers;

use DateTime;

class dateValidators
{
    public static function validateDate($date, $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        if ($d === false) {
            return false;
        }
        return $d->format($format) === $date;
    }
}
