<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Helper;

use LogicException;
use Vigihdev\WpCliModels\UI\WpCliStyle;

final class SpinnerLoader
{
    /**
     * The process ID of the spinner loader.
     */
    private int $pid = 0;

    /**
     * The frames for the spinner animation.
     */
    private array $frames = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];

    /**
     * The interval for updating the spinner animation.
     */
    private int $interval = 100000;

    /**
     * The start time of the spinner loader.
     */
    private int $startTime;

    /**
     * The message to display while loading.
     */
    private ?string $message = null;

    /**
     * The current message being displayed.
     */
    private string $currentMessage = '';

    /**
     * Whether the spinner loader has been started.
     */
    private bool $started = false;

    /**
     * Whether the spinner loader has been finished.
     */
    private bool $finished = false;

    /**
     * The style object for outputting messages.
     */
    private WpCliStyle $io;
    public function __construct()
    {
        $this->io = new WpCliStyle();
    }

    /**
     * Start the spinner loader. fork process.
     * 
     * @param string $message The message to display while loading.
     * @return void 
     */
    public function start(string $message): void
    {

        if ($this->started) {
            throw new LogicException('Spinner loader already started.');
        }

        printf("%s", "\e[?25l"); // hide cursor 

        $this->pid = pcntl_fork();

        if ($this->pid == -1) {
            echo "$message\n";
            return;
        }

        $this->started = true;
        $this->finished = false;
        $this->startTime = time();

        if ($this->pid > 0) {
            printf("%s", "\r\033[2K"); // Hapus baris (\r\033[2K)
            return;
        }

        $i = 0;
        while (true) {
            $frame = $this->frames[$i++ % count($this->frames)];
            $message = StyleConverter::convertTags($message);
            $this->currentMessage = "{$message}";
            printf("\r%s %s", $frame, $message);
            flush();
            usleep($this->interval);
        }
    }

    /**
     * Stop the spinner loader.
     * 
     * @param string $finalMessage The final message to display after loading.
     * @return void
     */
    public function stop(string $finalMessage): void
    {

        if ($this->pid > 0 && $this->started && !$this->finished) {
            // Munculkan kursor lagi (\e[?25h)
            $this->started = false;
            $this->finished = true;
            $message = StyleConverter::convertTags($finalMessage);
            posix_kill($this->pid, SIGKILL);
            pcntl_waitpid($this->pid, $status);
            printf("%s%s%s%s", "\r\033[2K", $message, strlen(trim($finalMessage)) > 0 ? PHP_EOL : '', "\e[?25h");
        }
    }
}
