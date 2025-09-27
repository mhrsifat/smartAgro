<?php

if (!function_exists('format_for_pdf')) {
    function format_for_pdf(?string $text): string
    {
        if (is_null($text)) {
            return '';
        }

        $bengaliRegex = '/[\x{0980}-\x{09FF}]+/u';

        return preg_replace($bengaliRegex, '<span class="bangla">$0</span>', $text);
    }
}
