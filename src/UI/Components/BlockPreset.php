<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;

final class BlockPreset
{

    public function __construct(
        private readonly CliStyle $io,
        private readonly string $message,
    ) {}

    public function success(): void
    {
        $io = $this->io;

        $padding = str_repeat(' ', 4);
        $message = " [OK] {$this->message} {$padding}";
        $background = str_repeat(' ', strlen($message));

        $io->log(WP_CLI::colorize("%2{$background}%n"));
        $io->log(WP_CLI::colorize("%2{$this->messageSuccess($message)}%n"));
        $io->log(WP_CLI::colorize("%2{$background}%n"));
    }
    public function error(): void
    {
        $io = $this->io;

        $padding = str_repeat(' ', 4);
        $message = " [ERROR] {$this->message} {$padding}";
        $background = str_repeat(' ', strlen($message));

        $io->log(WP_CLI::colorize("%1{$background}%n"));
        $io->log(WP_CLI::colorize("%1{$this->messageError($message)}%n"));
        $io->log(WP_CLI::colorize("%1{$background}%n"));
    }

    private function messageSuccess(string $message): string
    {
        return WP_CLI::colorize("%k{$message}%n");
    }

    private function messageError(string $message): string
    {
        return WP_CLI::colorize("%w{$message}%n");
    }
}
