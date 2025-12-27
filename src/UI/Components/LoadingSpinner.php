<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

final class LoadingSpinner
{
    private int $pid = 0;
    private array $frames = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];
    private int $interval = 100000; // microsecond

    public function __destruct()
    {
        echo "\e[?25h";
    }

    public function start(string $message): void
    {
        echo "\e[?25l"; // hide cursor

        $this->pid = pcntl_fork();

        if ($this->pid == -1) {
            echo "$message\n";
            return;
        }

        if ($this->pid > 0) {
            echo "\r\033[2K";
        } else {
            $i = 0;
            while (true) {
                printf("\r%s %s", $this->frames[$i++ % count($this->frames)], $message);
                flush();
                usleep($this->interval);
            }
        }
    }

    public function stop(string $finalMessage): void
    {
        if ($this->pid > 0) {
            posix_kill($this->pid, SIGKILL);
            pcntl_waitpid($this->pid, $status);

            // Bersihkan baris spinner, lalu cetak pesan final
            echo "\r\033[2K";
            echo $finalMessage . PHP_EOL;

            echo "\e[?25h"; // Munculkan kursor lagi
        }
    }
}
