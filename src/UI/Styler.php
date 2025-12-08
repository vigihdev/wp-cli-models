<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI;

use WP_CLI;

final class Styler
{
    /**
     * Icon mapping dengan warna default
     */
    private static $icons = [
        // ✅ Status
        'success'  => ['✅', '%G'],
        'error'    => ['❌', '%R'],
        'warning'  => ['⚠️', '%Y'],
        'info'     => ['💡', '%B'],
        'question' => ['❓', '%C'],

        // 📁 File & Folder
        'file'     => ['📄', '%M'],
        'folder'   => ['📁', '%C'],
        'file_add' => ['📝', '%G'],
        'file_del' => ['🗑️', '%R'],

        // 🔧 Process
        'process'  => ['🔄', '%Y'],
        'gear'     => ['⚙️', '%w'],
        'tools'    => ['🔧', '%w'],

        // 📊 Data
        'database' => ['🗄️', '%C'],
        'table'    => ['📊', '%B'],
        'chart'    => ['📈', '%G'],

        // 🚀 Action
        'start'    => ['🚀', '%G'],
        'stop'     => ['🛑', '%R'],
        'rocket'   => ['🚀', '%G'],
        'fire'     => ['🔥', '%R'],

        // 🔍 Search
        'search'   => ['🔍', '%B'],
        'find'     => ['🔎', '%B'],
        'target'   => ['🎯', '%M'],

        // 👤 User
        'user'     => ['👤', '%C'],
        'users'    => ['👥', '%B'],
        'key'      => ['🔑', '%Y'],
        'lock'     => ['🔒', '%Y'],

        // 📦 Package
        'package'  => ['📦', '%G'],
        'download' => ['📥', '%B'],
        'upload'   => ['📤', '%B'],

        // ⏱️ Time
        'time'     => ['⏱️', '%w'],
        'clock'    => ['🕐', '%w'],
        'hourglass' => ['⏳', '%Y'],

        // 🔌 Plugin & Theme
        'plugin'   => ['🔌', '%G'],
        'theme'    => ['🎨', '%B'],
        'wordpress' => ['⚙️', '%C'],

        // 🌐 Network
        'network'  => ['🌐', '%B'],
        'globe'    => ['🌍', '%C'],
        'link'     => ['🔗', '%B'],

        // 🎨 UI Elements
        'check'    => ['✓', '%G'],
        'cross'    => ['✗', '%R'],
        'star'     => ['⭐', '%Y'],
        'heart'    => ['❤️', '%R'],
        'flag'     => ['🚩', '%M'],
    ];

    /**
     * Tampilkan pesan dengan icon
     * 
     * @param string $type Tipe icon
     * @param string $message Pesan
     * @param string|null $color Warna custom (opsional)
     * @return string
     */
    public static function message(string $type, string $message, string $color = null): string
    {
        if (!isset(self::$icons[$type])) {
            return WP_CLI::colorize('%w' . $message . '%n');
        }

        list($icon, $default_color) = self::$icons[$type];
        $color = $color ?: $default_color;

        return WP_CLI::colorize($color . $icon . ' ' . $message . '%n');
    }

    /**
     * Tampilkan pesan langsung ke CLI
     */
    public static function line(string $type, string $message, string $color = null): void
    {
        WP_CLI::line(self::message($type, $message, $color));
    }

    /**
     * Tampilkan header dengan border
     */
    public static function header(string $title, string $color = '%B', string $border_char = '═'): void
    {
        $length = 50;
        $border = str_repeat($border_char, $length);

        WP_CLI::line(WP_CLI::colorize("\n%w" . $border . "%n"));
        WP_CLI::line(WP_CLI::colorize($color . '✨ ' . strtoupper($title) . '%n'));
        WP_CLI::line(WP_CLI::colorize("%w" . $border . "%n\n"));
    }

    /**
     * Tampilkan sub-header
     */
    public static function subheader(string $title, string $color = '%C'): void
    {
        WP_CLI::line(WP_CLI::colorize("\n" . $color . '▸ ' . $title . "%n"));
        WP_CLI::line(WP_CLI::colorize('%w' . str_repeat('─', strlen($title) + 2) . '%n'));
    }

    /**
     * Tampilkan separator
     */
    public static function separator(string $char = '─', int $length = 50, string $color = '%w'): void
    {
        WP_CLI::line(WP_CLI::colorize($color . str_repeat($char, $length) . '%n'));
    }

    /**
     * Tampilkan item list
     */
    public static function item(string $icon_type, string $label, $value = null, string $value_color = '%g'): void
    {
        if (!isset(self::$icons[$icon_type])) {
            $icon = '•';
            $icon_color = '%w';
        } else {
            list($icon, $icon_color) = self::$icons[$icon_type];
        }

        $output = '  ' . WP_CLI::colorize($icon_color . $icon . '%n') . ' ' . $label;

        if ($value !== null) {
            $output .= ': ' . WP_CLI::colorize($value_color . $value . '%n');
        }

        WP_CLI::line($output);
    }

    /**
     * Tampilkan list bullet
     */
    public static function bullet(string $message, int $indent = 0, string $color = '%w'): void
    {
        $indent_str = str_repeat('  ', $indent);
        WP_CLI::line($indent_str . WP_CLI::colorize($color . '• ' . $message . '%n'));
    }

    /**
     * Konfirmasi dengan style
     */
    public static function confirm(string $question, array $assoc_args = []): bool
    {
        $default_assoc_args = [
            'y' => true,
            'n' => false,
        ];

        $assoc_args = array_merge($default_assoc_args, $assoc_args);

        return WP_CLI::confirm(
            WP_CLI::colorize('%Y❔ ' . $question . '%n'),
            $assoc_args
        );
    }

    /**
     * Tampilkan progress bar dengan style
     */
    public static function progress(string $message, int $total, string $color = '%Y')
    {
        return \WP_CLI\Utils\make_progress_bar(
            WP_CLI::colorize($color . '🔄 ' . $message . '%n'),
            $total
        );
    }

    /**
     * Tampilkan success message
     */
    public static function success(string $message): void
    {
        WP_CLI::success($message);
        // Atau dengan custom:
        // WP_CLI::line(self::message('success', $message));
    }

    /**
     * Tampilkan error message
     */
    public static function error(string $message, bool $exit = true): void
    {
        if ($exit) {
            WP_CLI::error(self::message('error', $message));
        } else {
            WP_CLI::line(self::message('error', $message));
        }
    }

    /**
     * Tampilkan warning message
     */
    public static function warning(string $message): void
    {
        WP_CLI::warning($message);
        // Atau dengan custom:
        // WP_CLI::line(self::message('warning', $message));
    }

    /**
     * Tampilkan info box
     */
    public static function info_box(string $title, array $items, string $border_color = '%B'): void
    {
        self::separator('═', 50, $border_color);
        WP_CLI::line(WP_CLI::colorize($border_color . '📦 ' . strtoupper($title) . '%n'));
        self::separator('─', 50, $border_color);

        foreach ($items as $icon => $text) {
            self::item($icon, $text);
        }

        self::separator('═', 50, $border_color);
    }

    /**
     * Tampilkan tabel dengan style
     */
    public static function table(array $headers, array $data, string $format = 'table'): void
    {
        // Format headers
        $formatted_headers = array_map(function ($header) {
            return WP_CLI::colorize('%B' . $header . '%n');
        }, $headers);

        \WP_CLI\Utils\format_items($format, $data, $formatted_headers);
    }

    /**
     * Dapatkan semua icon yang tersedia
     */
    public static function get_available_icons(): array
    {
        return array_keys(self::$icons);
    }

    /**
     * Tambahkan icon custom
     */
    public static function add_icon(string $name, string $icon, string $color = '%w'): void
    {
        self::$icons[$name] = [$icon, $color];
    }
}
