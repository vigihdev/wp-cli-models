<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI;

use cli\Table;
use WP_CLI;

final class CliStyle
{

    public function title(string $msg, string $color = '%G'): void
    {
        WP_CLI::log("");
        WP_CLI::log(
            WP_CLI::colorize("{$color}{$msg}%n")
        );
        WP_CLI::log(str_repeat("=", strlen($msg)));
        WP_CLI::log("");
    }

    public function line(string $message): void
    {
        WP_CLI::line($message);
    }

    public function success(string $msg): void
    {
        WP_CLI::success("✅ {$msg}");
    }

    public function warning(string $msg): void
    {
        WP_CLI::warning("⚠️ {$msg}");
    }

    public function error(string $msg, bool $exit = true): void
    {
        WP_CLI::error("❌ {$msg}", $exit);
    }

    public function separator(string $char = '─', int $length = 50, string $color = '%w'): void
    {
        WP_CLI::line(
            WP_CLI::colorize($color . str_repeat($char, $length) . '%n')
        );
    }

    public function summary(...$messages): void
    {
        WP_CLI::log("");
        WP_CLI::log("📊 Summary:");
        WP_CLI::log("──────────────");
        foreach ($messages as $message) {
            WP_CLI::log("{$message}");
        }
        WP_CLI::log("");
    }

    public function textGreen(string $message, string $color = '%G'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }

    public function textYellow(string $message, string $color = '%Y'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }

    public function textMagenta(string $message, string $color = '%P'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }

    public function textRed(string $message, string $color = '%R'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }
    public function textBlue(string $message, string $color = '%b'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }

    public function textSuccess(string $message): string
    {
        return WP_CLI::colorize("%g{$message}%n");
    }

    public function textError(string $message): string
    {
        return WP_CLI::colorize("%r{$message}%n");
    }

    public function textInfo(string $message): string
    {
        return WP_CLI::colorize("%b{$message}%n");
    }
    public function textWarning(string $message): string
    {
        return WP_CLI::colorize("%y{$message}%n");
    }

    public function start(string $message)
    {
        WP_CLI::line("🚀 {$message}");
        WP_CLI::log('');
    }


    /**
     * @param string[] $fields
     * @param array<int,string[]> $items 
     */
    public function table(array $items, array $fields)
    {
        $fields = array_map(function ($v) {
            $v = ucfirst($v);
            return WP_CLI::colorize("%G{$v}%n");
        }, $fields);
        $table = new Table();
        $table->setHeaders($fields);
        $table->setRows($items);

        $table->display();
    }
}
