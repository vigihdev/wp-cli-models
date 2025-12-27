<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use DateTimeImmutable;
use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP;
use WP_CLI;

/**
 * ProcessTimerPreset - Untuk mengukur waktu eksekusi dengan checkpoint
 * Penggunaan:
 * 
 * ```php
 * $timer = new ProcessTimerPreset("Nama Proses");
 * $timer->start();
 * $timer->checkpoint("Step 1");
 * $timer->checkpoint("Step 2");
 * $timer->stop();
 * ```
 * 
 * Atau quick measure:
 * ProcessTimerPreset::quickMeasure(fn() => heavyOperation(), "Nama");
 */
final class ProcessTimerPreset
{
    private bool $isDone = false;
    private bool $isProcessing = false;
    private float $startTime;
    private float $endTime;
    private string $spin = '';
    private array $checkpoints = [];

    private readonly CliStyle $io;

    public function __construct(
        private readonly string $processName
    ) {
        $this->io = new CliStyle();
        $this->startTime = microtime(true);
    }

    public function start(): void
    {
        $io = $this->io;
        $date = WP_CLI::colorize("%c[{$this->getDate()}]%n");
        $name = WP_CLI::colorize("%g{$this->processName}%n");

        $io->line(
            sprintf("%s %s", $date, $name)
        );
    }

    public function checkpoint(string $name): void
    {
        $io = $this->io;

        $elapsed = microtime(true) - $this->startTime;
        $this->checkpoints[] = [
            'name' => $name,
            'time' => $elapsed
        ];

        $date = WP_CLI::colorize("%c[{$this->getDate()}]%n");
        $name = WP_CLI::colorize("%g{$name}%n");

        $io->line(
            sprintf("%s %s", $date, $name)
        );
    }

    public function stop(): float
    {
        $this->endTime = microtime(true);
        $duration = $this->getDuration();

        $this->showReport();

        return $duration;
    }

    private function showReport(): void
    {
        $io = $this->io;
        $duration = $this->getDuration();
        $date = WP_CLI::colorize("%c[{$this->getDate()}]%n");
        $name = WP_CLI::colorize("%gEND {$this->processName}%n");

        $io->line(
            sprintf("%s %s %s", $date, $name, $io->textGreen("{$duration}s"))
        );
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

    private function getDate(): string
    {
        $timeZone = new \DateTimeZone('Asia/Jakarta');
        $date = new DateTimeImmutable('now', $timeZone);
        return $date->format('H:i:s');
    }
}
