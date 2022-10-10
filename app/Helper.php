<?php

namespace App;

class Helper
{
    public static function extractNumbersFromString(?string $value): int
    {
        return (int) preg_replace('/[^0-9]/', '', trim($value ?? ''));
    }

    public static function translations($json)
    {
        if(!file_exists($json)) {
            return [];
        }

        return json_decode(file_get_contents($json), true);
    }
}
