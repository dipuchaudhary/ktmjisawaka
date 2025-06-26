<?php

if (!function_exists('toNepaliNumber')) {
    function toNepaliNumber($number)
    {
        $engToNepali = [
            '0' => '०',
            '1' => '१',
            '2' => '२',
            '3' => '३',
            '4' => '४',
            '5' => '५',
            '6' => '६',
            '7' => '७',
            '8' => '८',
            '9' => '९',
            '.' => '.', // keep decimal point
            '/' => '/', // keep slash if fiscal year format
            ',' => ',', // optional: keep commas
        ];

        return strtr((string) $number, $engToNepali);
    }
}
