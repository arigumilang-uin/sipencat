<?php

if (!function_exists('formatDateTime')) {
    /**
     * Format datetime untuk display di Indonesia
     * 
     * @param mixed $datetime
     * @param string $format
     * @return string
     */
    function formatDateTime($datetime, string $format = 'd F Y, H:i'): string
    {
        if (!$datetime) {
            return '-';
        }

        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        return $datetime->translatedFormat($format);
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format date saja (tanpa waktu)
     * 
     * @param mixed $date
     * @return string
     */
    function formatDate($date): string
    {
        return formatDateTime($date, 'd F Y');
    }
}

if (!function_exists('formatTime')) {
    /**
     * Format time saja (tanpa tanggal)
     * 
     * @param mixed $time
     * @return string
     */
    function formatTime($time): string
    {
        return formatDateTime($time, 'H:i');
    }
}

if (!function_exists('formatDateTimeShort')) {
    /**
     * Format datetime versi pendek
     * 
     * @param mixed $datetime
     * @return string
     */
    function formatDateTimeShort($datetime): string
    {
        return formatDateTime($datetime, 'd/m/Y H:i');
    }
}

if (!function_exists('diffForHumans')) {
    /**
     * Format relative time dalam Bahasa Indonesia
     * 
     * @param mixed $datetime
     * @return string
     */
    function diffForHumans($datetime): string
    {
        if (!$datetime) {
            return '-';
        }

        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        return $datetime->diffForHumans();
    }
}

if (!function_exists('nowIndonesia')) {
    /**
     * Get current time in Indonesia timezone
     * 
     * @return \Carbon\Carbon
     */
    function nowIndonesia(): \Carbon\Carbon
    {
        return \Carbon\Carbon::now(config('app.timezone'));
    }
}

if (!function_exists('todayIndonesia')) {
    /**
     * Get today's date in Indonesia timezone
     * 
     * @return \Carbon\Carbon
     */
    function todayIndonesia(): \Carbon\Carbon
    {
        return \Carbon\Carbon::today(config('app.timezone'));
    }
}
