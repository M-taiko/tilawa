<?php

if (!function_exists('toArabicNumerals')) {
    function toArabicNumerals(int|string $number): string
    {
        return strtr((string) $number, '0123456789', '٠١٢٣٤٥٦٧٨٩');
    }
}
