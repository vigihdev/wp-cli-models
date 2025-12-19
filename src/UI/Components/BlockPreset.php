<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;

final class BlockPreset
{

    private int $width = 100;
    private int $lengthMessage = 0;
    public function __construct(
        private readonly CliStyle $io,
        private readonly string $message,
    ) {
        $this->lengthMessage = strlen($this->message);
    }

    public function success(): void
    {
        $io = $this->io;

        $message = " [OK] {$this->message}";
        $message = sprintf("%s%s", $message, str_repeat(' ', $this->width - strlen($message)));
        $background = str_repeat(' ', $this->width);

        $text = WP_CLI::colorize("%k{$message}%n");
        $io->log(WP_CLI::colorize("%2{$background}%n"));
        $io->log(WP_CLI::colorize("%2{$text}%n"));
        $io->log(WP_CLI::colorize("%2{$background}%n"));
    }
    public function error(): void
    {
        $io = $this->io;

        // Menambahkan spasi di depan pesan error
        $message = " [ERROR] {$this->message}";
        $message = sprintf("%s%s", $message, str_repeat(' ', $this->width - strlen($message)));
        $background = str_repeat(' ', $this->width);

        $text = WP_CLI::colorize("%w{$message}%n");
        $io->log(WP_CLI::colorize("%1{$background}%n"));
        $io->log(WP_CLI::colorize("%w{$text}%n"));
        $io->log(WP_CLI::colorize("%1{$background}%n"));
    }

    private function info(): void
    {
        $io = $this->io;

        $message = " [INFO] {$this->message}";
        $message = sprintf("%s%s", $message, str_repeat(' ', $this->width - strlen($message)));
        $background = str_repeat(' ', $this->width);

        $text = WP_CLI::colorize("%w{$message}%n");
        $io->log(WP_CLI::colorize("%3{$background}%n"));
        $io->log(WP_CLI::colorize("%3{$text}%n"));
        $io->log(WP_CLI::colorize("%3{$background}%n"));
    }

    private function warning(): void
    {
        $io = $this->io;

        $message = " [WARNING] {$this->message}";
        $message = sprintf("%s%s", $message, str_repeat(' ', $this->width - strlen($message)));
        $background = str_repeat(' ', $this->width);

        $text = WP_CLI::colorize("%y{$message}%n");
        $io->log(WP_CLI::colorize("%3{$background}%n"));
        $io->log(WP_CLI::colorize("%3{$text}%n"));
        $io->log(WP_CLI::colorize("%3{$background}%n"));
    }
}
