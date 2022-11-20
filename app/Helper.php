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
        if (!file_exists($json)) {
            return [];
        }

        return json_decode(file_get_contents($json), true);
    }

    static function formatinr($input)
    {
        //CUSTOM FUNCTION TO GENERATE ##,##,###.##
        $dec = "";
        $pos = strpos($input, ".");
        if ($pos === false) {
            //no decimals   
        } else {
            //decimals
            $dec = substr(round(substr($input, $pos), 2), 1);
            $input = substr($input, 0, $pos);
        }
        $num = substr($input, -3); //get the last 3 digits
        $input = substr($input, 0, -3); //omit the last 3 digits already stored in $num
        while (strlen($input) > 0) //loop the process - further get digits 2 by 2
        {
            $num = substr($input, -2) . "," . $num;
            $input = substr($input, 0, -2);
        }
        return $num . $dec;
    }

    public static function formatCurrency($floatcurr, $curr = "USD")
    {
        $currencies['BRL'] = array(2, ',', '.');          //  Brazilian Real
        $currencies['EUR'] = array(2, ',', '.');          //  Euro
        $currencies['USD'] = array(2, '.', ',');          //  US Dollar

        if ($curr == "INR") {
            return self::formatinr($floatcurr);
        } else {
            return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
        }
    }
}
