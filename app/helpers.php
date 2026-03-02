<?php

if (!function_exists('toArabicNumerals')) {
    function toArabicNumerals(int|string|null $number): string
    {
        if ($number === null) return '';
        return strtr((string) $number, '0123456789', '٠١٢٣٤٥٦٧٨٩');
    }
}
