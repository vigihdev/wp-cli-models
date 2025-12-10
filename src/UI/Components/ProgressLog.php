<?php

namespace Vigihdev\WpCliModels\UI\Components;

use WP_CLI;

final class ProgressLog
{
    private int $current = 0;
    private int $total = 0;

    public function __construct(int $total)
    {
        $this->total = max(1, $total);
    }

    /**
     * Tampilkan opening log
     */
    public function start(string $message = '⏳ Memproses...'): void
    {
        WP_CLI::log($message);
    }

    /**
     * Log item sedang diproses
     */
    public function processing(string $title): void
    {
        $this->current++;
        WP_CLI::log(
            sprintf("[%d/%d] 📝 Processing: %s", $this->current, $this->total, $title)
        );
    }

    /**
     * Log warning dengan style seragam
     */
    public function warn(string $message): void
    {
        WP_CLI::warning("⚠️  " . $message);
    }

    /**
     * Log sukses per-item
     */
    public function success(string $message): void
    {
        WP_CLI::success($message);
    }

    /**
     * Log error per-item
     */
    public function error(string $message): void
    {
        WP_CLI::error($message, false); // false = jangan exit
    }
}
