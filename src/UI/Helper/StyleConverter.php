<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Helper;


final class StyleConverter
{

    public static function convertTags(string $text): string
    {
        // Tangani tag khusus yang pakai icon (Success & Warning)
        $text = str_replace('<success>', '%g✅ ', $text);
        $text = str_replace('<warning>', '%y⚠️ ', $text);

        // Pola ini akan mencari kata kunci warna/opsi di dalam tag
        $text = preg_replace_callback('/<([^>]+)>/', function ($matches) {
            $content = $matches[1];

            if ($content[0] === '/') {
                return '%n';
            }

            $parts = explode(';', $content);
            $result = '';

            foreach ($parts as $part) {
                $map = [
                    'info' => '%g',
                    'comment' => '%y',
                    'error' => '%r',
                    'question' => '%c',

                    // Background colors  
                    'bg=red' => '%1',
                    'bg=yellow' => '%3',
                    'bg=green' => '%2',
                    'bg=blue' => '%4',
                    'bg=magenta' => '%5',
                    'bg=cyan' => '%6',
                    'bg=white' => '%7',
                    'bg=black' => '%0',

                    'fg=green' => '%g',
                    'fg=red' => '%r',
                    'fg=blue' => '%b',
                    'fg=yellow' => '%y',
                    'fg=magenta' => '%m',
                    'fg=cyan' => '%c',
                    'fg=black' => '%k',
                    'fg=white' => '%w',

                    'options=bold' => '%B',
                    'options=underscore' => '%U',

                ];

                if (isset($map[trim($part)])) {
                    $result .= $map[trim($part)];
                }
            }

            return $result;
        }, $text);

        $text = self::colorize($text);
        $text = str_replace('%n%n', '%n', $text);
        return $text;
    }

    private static function colorize(string $text): string
    {

        $placeholders = [
            '%n' => "\e[0m",  // Reset
            '%B' => "\e[1m",  // Bold
            '%U' => "\e[4m",  // Underline

            // Foreground
            '%r' => "\e[31m",
            '%g' => "\e[32m",
            '%y' => "\e[33m",
            '%b' => "\e[34m",
            '%m' => "\e[35m",
            '%c' => "\e[36m",
            '%w' => "\e[37m",
            '%k' => "\e[30m",

            // Background
            '%1' => "\e[41m",
            '%2' => "\e[42m",
            '%3' => "\e[43m",
            '%4' => "\e[44m",
            '%5' => "\e[45m",
            '%6' => "\e[46m",
            '%7' => "\e[47m",
            '%0' => "\e[40m",
        ];

        return strtr($text, $placeholders) . "\e[0m";
    }
}
