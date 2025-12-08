<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI;

use WP_CLI;

final class CliStyle
{

    public function title(string $msg): void
    {
        WP_CLI::log("");
        WP_CLI::log(
            WP_CLI::colorize("%B{$msg}%n")
        );
        WP_CLI::log(str_repeat("=", strlen($msg)));
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

    public function summary(int $total, int $created, int $skipped, int $failed, float $seconds): void
    {
        WP_CLI::log("");
        WP_CLI::log("📊 Summary:");
        WP_CLI::log("──────────────");
        WP_CLI::log("Total   : {$total}");
        WP_CLI::log("✅ Created: {$created}");
        WP_CLI::log("⚠️ Skipped: {$skipped}");
        WP_CLI::log("❌ Failed : {$failed}");
        WP_CLI::log("Time    : {$seconds}s");
    }
}
