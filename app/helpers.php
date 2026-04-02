<?php

if (!function_exists('toArabicNumerals')) {
    function toArabicNumerals(int|string|null $number): string
    {
        if ($number === null) return '';
        return str_replace(
            ['0','1','2','3','4','5','6','7','8','9'],
            ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'],
            (string) $number
        );
    }
}
