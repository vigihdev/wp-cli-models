<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;

final class ProcessExportPreset
{
    private ImportSummary $summary;
    private ProgressLog $progressLog;
    private AskPreset $ask;

    public function __construct(
        private readonly CliStyle $io,
        private readonly string $output,
        private readonly string|float $startTime,
        private readonly string $name,
        private readonly int $total,
    ) {
        $this->progressLog = new ProgressLog(io: $io, total: $total);
        $this->summary = new ImportSummary();
        $this->ask = new AskPreset(io: $io);
    }

    public function startRender(): void
    {
        $io = $this->io;
        $io->title('🚀 Memulai Export ' . $this->name);
        $io->hr();

        $this->ask->directory($this->output);
    }

    private function getStartTime(): string|float
    {
        return $this->startTime;
    }

    private function getSummary(): ImportSummary
    {
        return $this->summary;
    }

    private function getProgressLog(): ProgressLog
    {
        return $this->progressLog;
    }
}
