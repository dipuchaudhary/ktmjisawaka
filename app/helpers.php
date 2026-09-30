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


if (!function_exists('nextFiscalSequence')) {
    function nextFiscalSequence(string $modelClass, string $column = 'challani_number'): int
    {
        $max = 0;

        foreach ($modelClass::query()->whereNotNull($column)->pluck($column) as $value) {
            if (preg_match('/-(\d+)$/u', (string) $value, $matches)) {
                $max = max($max, (int) $matches[1]);
            }
        }

        return $max + 1;
    }
}
