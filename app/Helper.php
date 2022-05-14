<?php

namespace App;

class Helper {
    public static function extractNumbersFromString(?string $value): int
    {
        return (int) preg_replace('/[^0-9]/', '', trim($value ?? ''));
    }
}