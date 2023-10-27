<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DateLocalizationService
{
    public static function localize(Carbon $date): string
    {

        $monthNames = [
            "Jan" => "января",
            "Feb" => "февраля",
            "Mar" => "марта",
            "Apr" => "апреля",
            "May" => "мая",
            "Jun" => "июня",
            "Jul" => "июля",
            "Aug" => "августа",
            "Sep" => "сентября",
            "Oct" => "октября",
            "Nov" => "ноября",
            "Dec" => "декабря"
        ];

        return $date->day . ' ' . $monthNames[$date->format('M')] . ' ' . $date->year;
    }
}
