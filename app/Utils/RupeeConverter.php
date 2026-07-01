<?php

namespace App\Utils;

class RupeeConverter
{
    public static function convert(float $number): string
    {
        $amount = round($number, 2);
        $rupees = floor($amount);
        $paise = round(($amount - $rupees) * 100);

        $rupeesWords = self::getWords($rupees);
        $paiseWords = $paise > 0 ? self::getWords($paise) . " Paise" : "";

        if (empty($rupeesWords) && empty($paiseWords)) {
            return "Zero Rupees Only";
        }

        $result = !empty($rupeesWords) ? "Rupees " . $rupeesWords : "";
        if (!empty($paiseWords)) {
            $result .= (!empty($result) ? " and " : "") . $paiseWords;
        }

        return $result . " Only";
    }

    private static function getWords(float $num): string
    {
        if ($num == 0) return "";

        $ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
        $tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

        if ($num < 20) {
            return $ones[$num];
        }
        if ($num < 100) {
            return $tens[floor($num / 10)] . ($num % 10 ? " " . $ones[$num % 10] : "");
        }
        if ($num < 1000) {
            return $ones[floor($num / 100)] . " Hundred" . ($num % 100 ? " and " . self::getWords($num % 100) : "");
        }
        if ($num < 100000) {
            return self::getWords(floor($num / 1000)) . " Thousand" . ($num % 1000 ? " " . self::getWords($num % 1000) : "");
        }
        if ($num < 10000000) {
            return self::getWords(floor($num / 100000)) . " Lakh" . ($num % 100000 ? " " . self::getWords($num % 100000) : "");
        }
        
        return self::getWords(floor($num / 10000000)) . " Crore" . ($num % 10000000 ? " " . self::getWords($num % 10000000) : "");
    }
}
