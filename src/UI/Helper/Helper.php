<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Helper;

use Symfony\Component\String\UnicodeString;

abstract class Helper
{

    /**
     * Get the width of a string.
     * 
     * @param string|null $string
     * @return int
     */
    public static function width(?string $string): int
    {
        $string ??= '';

        if (preg_match('//u', $string)) {
            $string = preg_replace('/[\p{Cc}\x7F]++/u', '', $string, -1, $count);

            return (new UnicodeString($string))->width(false) + $count;
        }

        if (false === $encoding = mb_detect_encoding($string, null, true)) {
            return \strlen($string);
        }

        return mb_strwidth($string, $encoding);
    }

    /**
     * Get the length of a string.
     * 
     * @param string|null $string
     * @return int
     */
    public static function length(?string $string): int
    {
        $string ??= '';

        if (preg_match('//u', $string)) {
            return (new UnicodeString($string))->length();
        }

        if (false === $encoding = mb_detect_encoding($string, null, true)) {
            return \strlen($string);
        }

        return mb_strlen($string, $encoding);
    }

    /**
     * Get a substring of a string. 
     * 
     * @param string|null $string The string to get the substring from.
     * @param int $from The position to start from.
     * @param int|null $length The length of the substring.
     * @return string The substring.
     */
    public static function substr(?string $string, int $from, ?int $length = null): string
    {
        $string ??= '';

        if (preg_match('//u', $string)) {
            return (new UnicodeString($string))->slice($from, $length)->toString();
        }

        if (false === $encoding = mb_detect_encoding($string, null, true)) {
            return substr($string, $from, $length);
        }

        return mb_substr($string, $from, $length, $encoding);
    }

    /**
     * Format a time in milliseconds to a human readable string.
     * 
     * @param int|float $secs The time in milliseconds.
     * @param int $precision The precision of the time.
     * @return string The formatted time.
     */
    public static function formatTime(int|float $secs, int $precision = 1): string
    {
        $ms = (int) ($secs * 1000);
        $secs = (int) floor($secs);

        if (0 === $ms) {
            return '< 1 ms';
        }

        static $timeFormats = [
            [1, 'ms'],
            [1000, 's'],
            [60000, 'min'],
            [3600000, 'h'],
            [86_400_000, 'd'],
        ];

        $times = [];
        foreach ($timeFormats as $index => $format) {
            $milliSeconds = isset($timeFormats[$index + 1]) ? $ms % $timeFormats[$index + 1][0] : $ms;

            if (isset($times[$index - $precision])) {
                unset($times[$index - $precision]);
            }

            if (0 === $milliSeconds) {
                continue;
            }

            $unitCount = ($milliSeconds / $format[0]);
            $times[$index] = $unitCount . ' ' . $format[1];

            if ($ms === $milliSeconds) {
                break;
            }

            $ms -= $milliSeconds;
        }

        return implode(', ', array_reverse($times));
    }

    /**
     * Format a memory in bytes to a human readable string.
     * 
     * @param int $memory The memory in bytes.
     * @return string The formatted memory.
     */
    public static function formatMemory(int $memory): string
    {
        if ($memory >= 1024 * 1024 * 1024) {
            return \sprintf('%.1f GiB', $memory / 1024 / 1024 / 1024);
        }

        if ($memory >= 1024 * 1024) {
            return \sprintf('%.1f MiB', $memory / 1024 / 1024);
        }

        if ($memory >= 1024) {
            return \sprintf('%d KiB', $memory / 1024);
        }

        return \sprintf('%d B', $memory);
    }

    public static function textJustifyKey(array $assoc_args): array
    {

        $lengths = array_map(function ($value) {
            $lengthValue = strlen((string)$value);
            return $lengthValue;
        }, array_keys($assoc_args));

        $length = max($lengths);
        $items = [];
        foreach ($assoc_args as $key => $value) {
            $padding = str_repeat(' ', $length - strlen($key));
            $items[$key] = "{$padding}{$value}";
        }

        return $items;
    }
}
