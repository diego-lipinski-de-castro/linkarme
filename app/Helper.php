<?php

namespace App;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Illuminate\Support\Facades\Log;

class Helper
{
    public static function extractNumbersFromString(?string $value): int
    {
        return (int) preg_replace('/[^0-9]/', '', trim($value ?? ''));
    }

    public static function translations($json)
    {
        if (!file_exists($json)) {
            return [];
        }

        return json_decode(file_get_contents($json), true);
    }

    public static function formatCurrency(float $floatcurr, string $curr = "USD")
    {
        $newCurr = (int) ($floatcurr * 100);

        $options['BRL'] = 'pt_BR';
        $options['EUR'] = 'nl_NL';
        $options['USD'] = 'en_US';
        $options['GBP'] = 'en_GB';

        $money = new Money($newCurr, new Currency($curr));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter($options[$curr], \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money); // outputs $1.00
    }
}
