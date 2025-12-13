<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\UI\CliStyle;

final class ProcessExportPreset
{
    private ImportSummary $summary;
    private ProgressLog $progressLog;
    private bool $successAsk = false;
    private AskPreset $ask;

    public function __construct(
        private readonly CliStyle $io,
        private readonly string $output,
        private readonly string $format,
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
        $output = Path::isAbsolute($this->output) ? $this->output : Path::join(getcwd() ?? '', $this->output);

        // $io->line(
        //     sprintf("📁 Export akan disimpan di %s", $io->highlightText($output))
        // );

        $io->line(
            sprintf("%s 📁 Cek directory %s", $io->textInfo('[INFO]'), $io->highlightText($output))
        );
        $this->successAsk = $this->ask->directory($this->output);
        sleep(1);
        if ($this->successAsk) {
            $execTime = $this->countingInSeconds();
            $io->line(
                sprintf("%s 📁 Cek directory %s detik", $io->textInfo('[SUCCESS]'), $io->highlightText($execTime))
            );
        }
    }

    public function getSuccessAsk(): bool
    {
        return $this->successAsk;
    }

    public function countingInSeconds(): string
    {
        return number_format(microtime(true) - $this->startTime, 2);
    }

    public function getStartTime(): string|float
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
