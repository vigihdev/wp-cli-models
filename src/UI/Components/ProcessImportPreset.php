<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;

final class ProcessImportPreset
{
    private ImportSummary $summary;
    private ProgressLog $progressLog;

    public function __construct(
        private readonly CliStyle $io,
        private readonly string $filepath,
        private readonly string|float $startTime,
        private readonly string $name,
        private readonly int $total,
    ) {
        $this->progressLog = new ProgressLog(io: $io, total: $total);
        $this->summary = new ImportSummary();
    }

    public function getStartTime(): string|float
    {
        return $this->startTime;
    }

    public function startRender(): void
    {
        $io = $this->io;
        $io->title('🚀 Memulai Import ' . $this->name);
        $io->note('Mode: EXECUTE - Data akan dimasukkan ke database');
        $io->hr();

        $io->info("📊 Menemukan {$this->total} {$this->name}(s) untuk diimport.");
        $io->newLine();
        $this->progressLog->start();
    }

    public function getSummary(): ImportSummary
    {
        return $this->summary;
    }

    public function getProgressLog(): ProgressLog
    {
        return $this->progressLog;
    }
}
