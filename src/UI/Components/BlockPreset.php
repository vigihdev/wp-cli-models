<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;


/**
 * BlockPreset adalah kelas yang digunakan untuk menampilkan blok preset pesan di CLI.
 * 
 * ```php
 * $color = [
 *  '%g' => ['color' => 'green'],
 *  '%b' => ['color' => 'blue'],
 *  '%r' => ['color' => 'red'],
 *  '%p' => ['color' => 'magenta'],
 *  '%m' => ['color' => 'magenta'],
 *  '%c' => ['color' => 'cyan'],
 *  '%w' => ['color' => 'grey'],
 *  '%k' => ['color' => 'black'],
 *  '%n' => ['color' => 'reset'],
 *  '%Y' => ['color' => 'yellow', 'style' => 'bright'],
 *  '%G' => ['color' => 'green', 'style' => 'bright'],
 *  '%B' => ['color' => 'blue', 'style' => 'bright'],
 *  '%R' => ['color' => 'red', 'style' => 'bright'],
 *  '%P' => ['color' => 'magenta', 'style' => 'bright'],
 *  '%M' => ['color' => 'magenta', 'style' => 'bright'],
 *  '%C' => ['color' => 'cyan', 'style' => 'bright'],
 *  '%W' => ['color' => 'grey', 'style' => 'bright'],
 *  '%K' => ['color' => 'black', 'style' => 'bright'],
 *  '%N' => ['color' => 'reset', 'style' => 'bright'],
 *  '%3' => ['background' => 'yellow'],
 *  '%2' => ['background' => 'green'],
 *  '%4' => ['background' => 'blue'],
 *  '%1' => ['background' => 'red'],
 *  '%5' => ['background' => 'magenta'],
 *  '%6' => ['background' => 'cyan'],
 *  '%7' => ['background' => 'grey'],
 *  '%0' => ['background' => 'black'],
 *  '%F' => ['style' => 'blink'],
 *  '%U' => ['style' => 'underline'],
 *  '%8' => ['style' => 'inverse'],
 *  '%9' => ['style' => 'bright'],
 *  '%_' => ['style' => 'bright'],
 * ];
 * ```
 */
final class BlockPreset
{

    private int $width = 100;
    private int $lengthMessage = 0;
    public function __construct(
        private readonly CliStyle $io,
        private string $message,
    ) {
        $this->lengthMessage = strlen($this->message);
    }

    /**
     * Menampilkan blok preset pesan sukses
     * 
     * @return void
     */
    public function success(): void
    {
        $io = $this->io;

        $message = " [OK] {$this->message}";
        $width = $this->getWidth($message);
        $background = str_repeat(' ', $width);

        $text = WP_CLI::colorize("%k{$this->message}%n");
        $io->log(WP_CLI::colorize("%2{$background}%n"));
        $io->log(WP_CLI::colorize("%2{$text}%n"));
        $io->log(WP_CLI::colorize("%2{$background}%n"));
    }
    /**
     * Menampilkan blok preset pesan error
     * 
     * @return void
     */
    public function error(): void
    {
        $io = $this->io;

        // Menambahkan spasi di depan pesan error
        $message = " [ERROR] {$this->message}";
        $width = $this->getWidth($message);
        $background = str_repeat(' ', $width);

        $text = WP_CLI::colorize("%w{$this->message}%n");
        $io->log(WP_CLI::colorize("%1{$background}%n"));
        $io->log(WP_CLI::colorize("%1{$text}%n"));
        $io->log(WP_CLI::colorize("%1{$background}%n"));
    }

    /**
     * Menampilkan blok preset pesan informasi
     */
    public function info(): void
    {
        $io = $this->io;

        $message = " [INFO] {$this->message}";
        $width = $this->getWidth($message);
        $background = str_repeat(' ', $width);

        $text = WP_CLI::colorize("%k{$this->message}%n");
        $io->log(WP_CLI::colorize("%4{$background}%n"));
        $io->log(WP_CLI::colorize("%4{$text}%n"));
        $io->log(WP_CLI::colorize("%4{$background}%n"));
    }


    /**
     * Menampilkan blok preset pesan peringatan
     */
    public function warning(): void
    {
        $io = $this->io;

        $message = " [WARNING] {$this->message}";
        $width = $this->getWidth($message);
        $background = str_repeat(' ', $width);

        $text = WP_CLI::colorize("%k{$this->message}%n");
        $io->log(WP_CLI::colorize("%3{$background}%n"));
        $io->log(WP_CLI::colorize("%3{$text}%n"));
        $io->log(WP_CLI::colorize("%3{$background}%n"));
    }

    /**
     * Menghitung lebar blok preset pesan
     * 
     * @param string $message Pesan yang akan ditampilkan
     * @return int Lebar blok preset pesan
     */
    private function getWidth(string $message): int
    {
        $length = $this->width - strlen($message);
        if ($length > 0) {
            $this->message = sprintf('%s%s', $message, str_repeat(' ', $length + 4));
            return $this->width;
        }

        $this->message = sprintf('%s%s', $message, str_repeat(' ', 4));
        return strlen($message) + 4;
    }
}
