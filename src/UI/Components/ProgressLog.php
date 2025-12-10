<?php

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;

final class ProgressLog
{
    private int $current = 0;

    public function __construct(
        private readonly CliStyle $io,
        private readonly int $total = 0,
        private readonly bool $blockHr = true,
    ) {}


    /**
     * Tampilkan opening log
     */
    public function start(string $message = '⏳ Memproses...'): void
    {
        if ($this->blockHr) {
            $this->io->hr('-', 75);
        }
        WP_CLI::log($message);
    }

    public function end(): void
    {
        if ($this->blockHr) {
            $this->io->hr('-', 75);
        }
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
    public function warning(string $message): void
    {
        $this->io->warningWithIcon($message);
    }

    /**
     * Log sukses per-item
     */
    public function success(string $message): void
    {
        $this->io->successWithIcon($message);
    }

    /**
     * Log error per-item
     */
    public function error(string $message): void
    {
        $this->io->errorWithIcon($message);
    }
}
