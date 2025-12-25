<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\UI\CliStyle;

/**
 * ProcessTimerPreset - Untuk mengukur waktu eksekusi dengan checkpoint
 * Penggunaan:
 * 1. $timer = new ProcessTimerPreset("Nama Proses");
 * 2. $timer->checkpoint("Step 1");
 * 3. $timer->stop();
 * 
 * Atau quick measure:
 * ProcessTimerPreset::quickMeasure(fn() => heavyOperation(), "Nama");
 */
final class ProcessTimerPreset
{
    private float $startTime;
    private float $endTime;
    private array $checkpoints = [];

    public function __construct(
        private readonly string $processName = 'Proses'
    ) {
        $this->startTime = microtime(true);
    }

    private function start(): void {}

    private function checkpoint(string $name): void
    {

        $elapsed = microtime(true) - $this->startTime;
        $this->checkpoints[] = [
            'name' => $name,
            'time' => $elapsed
        ];
    }

    private function stop(): float
    {
        $this->endTime = microtime(true);
        $duration = $this->getDuration();

        $this->showReport();

        return $duration;
    }

    private function showReport(): void
    {
        $duration = $this->getDuration();
    }

    private function getDuration(): float
    {
        return microtime(true) - $this->startTime;
    }

    private static function quickMeasure(callable $callback, string $name = 'Proses'): mixed
    {
        $timer = new self($name);
        $result = $callback();
        $timer->stop();
        return $result;
    }
}
