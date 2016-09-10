<?php

use Carbon\Carbon;

if (!function_exists('dash_case')) {
    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    function dash_case($str) {
        return str_replace('_', '-', snake_case($str));
    }
}

if (!function_exists('right')) {
    /**
     * Get right from a number
     */
    function right($n)
    {
        return 1 << $n;
    }
}

if (!function_exists('hasRight')) {
    /**
     * Check right
     */
    function hasRight($rights, $right)
    {
        $right = right($right);
        return ($rights & $right) === $right;
    }
}

if (!function_exists('getSizeInBytes')) {
    /**
     * Get size(3K/2M/1G) in bytes
     * @param $size {string}
     * @return {number} size in bytes
     */
    function getSizeInBytes($size)
    {
        $size = trim($size);
        $modifier = strtolower($size[strlen($size) - 1]);
        $size = intval($size);
        switch ($modifier) {
            case 'g':
                $size *= 1024;
            case 'm':
                $size *= 1024;
            case 'k':
                $size *= 1024;
                break;
            default:
                throw new \Exception('Unknown byte modifier '.$modifier);
        }

        return $size;
    }
}

if (!function_exists('formatSeconds')) {
    /**
     * convert seconds to aother units
     * @param $seconds {integer} seconds will be converted
     * @param $format {string} the format want convert, Y as year, K as month,
     *     D as day, M as minute, S as second. format follow this order
     */
    function formatSeconds($seconds, $format = 'HM')
    {
        $result = '';

        $formats = str_split(strtoupper($format));
        foreach($formats as $f) {
            switch ($f) {
                case 'Y':
                    $years = floor($seconds / (3600 * 24 * 365));
                    $seconds %= 3600 * 24 * 365;
                    if ($years > 0) {
                        $result .= $years.' 年 ';
                    }
                    break;
                case 'K':
                    $months = floor($seconds / (3600 * 24 * 30));
                    $seconds %= 3600 * 24 * 30;
                    if ($months > 0) {
                        $result .= $months.' 月 ';
                    }
                    break;
                case 'D':
                    $days = floor($seconds / (3600 * 24));
                    $seconds %= 3600 * 24;
                    if ($days > 0) {
                        $result .= $days.' 天 ';
                    }
                    break;
                case 'H':
                    $hours = floor($seconds / 3600);
                    $seconds %= 3600;
                    if ($hours > 0) {
                        $result .= $hours.' 小时 ';
                    }
                    break;
                case 'M':
                    $minutes = floor($seconds / 60);
                    $seconds %= 60;
                    if ($minutes > 0) {
                        $result .= $minutes.' 分钟 ';
                    }
                    break;
                case 'S':
                    if ($seconds > 0) {
                        $result .= $seconds.' 秒 ';
                    }
                    break;
                default:
                    break;
            }
        }

        return rtrim($result);
    }
}

if (!function_exists('regexp')) {
    /**
     * Test a string is a regular expression literal
     * and retrieve it
     *
     * @param $re {string} the raw string
     * @return {boolean|string}
     */
    function regexp($re)
    {
        $len = strlen($re);
        // r'/a/' is the shortest
        if ($len < 6) {
            return false;
        }
        // the regexp format is r'xxxxx'
        if ($re[0] !== 'r' || $re[1] !== '\'' || $re[$len - 1] !== '\'') {
            return false;
        }
        return substr($re, 2, -1);
    }
}

if (!function_exists('pending')) {
    /**
     * Check an exam is pending
     * @param $exam {object}
     */
    function pending($exam)
    {
        return Carbon::now() < $exam->start;
    }
}

if (!function_exists('ended')) {
    /**
     * Check an exam is ended
     * @param $exam {object}
     */
    function ended($exam)
    {
        return Carbon::now() > $exam->start->addSeconds($exam->duration);
    }
}

if (!function_exists('running')) {
    /**
     * Check an exam is running
     * @param $exam {object}
     */
    function running($exam)
    {
        return !pending($exam) && !ended($exam);
    }
}

if (!function_exists('por')) {
    /**
     * return the first non-null argument
     */
    function por($first, $second)
    {
        return $first ? $first : $second;
    }
}

if (!function_exists('pif')) {
    /**
     * if condition is true, return the value, or the empty string
     */
    function pif($condition, $value, $alter = '')
    {
        return $condition ? $value : $alter;
    }
}
