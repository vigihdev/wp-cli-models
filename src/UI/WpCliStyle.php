<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI;

use cli\Table;
use Vigihdev\WpCliModels\UI\Helper\Helper;
use Vigihdev\WpCliModels\UI\Helper\OutputWrapper;
use Vigihdev\WpCliModels\UI\Helper\StyleConverter;
use WP_CLI;

final class WpCliStyle
{
    public const MAX_LINE_LENGTH = 120;

    private int $lineLength;

    public function __construct()
    {
        $this->lineLength = min($this->detectTerminalWidth(), self::MAX_LINE_LENGTH);
        // $this->lineLength = min(80 - (int) (\DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);
    }

    private function detectTerminalWidth(): int
    {
        if (function_exists('shell_exec') && preg_match('#\d+ (\d+)#', shell_exec('stty size') ?: '', $m)) {
            return (int) $m[1];
        }
        return PHP_OS_FAMILY === 'Windows' ? 80 : 120;
    }

    private function blockWithIcon(string|array $message, string $style, string $icon): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, null, $style, ' ', true);
        foreach ($lines as $i => $line) {
            if ($i === 1) {
                $line = "{$icon}{$line}";
                $this->text($line);
                continue;
            }
            $this->text("  {$line}");
        }
    }

    public function title(string $message, string $color = 'fg=green;options=bold'): void
    {
        $this->text("<{$color}>{$message}</>");
        $this->hr('=', strlen($message));
        $this->newLine();
    }
    public function section(string $message, string $color = 'fg=yellow;options=bold'): void
    {

        $this->text("<{$color}>{$message}</>");
        $this->hr('=', strlen($message));
        $this->newLine();
    }

    public function note(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=yellow', '📝');
    }

    public function error(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=red', '❌');
    }

    public function caution(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=red', '🔥');
    }

    public function info(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=cyan', 'ℹ️');
    }

    public function warning(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=yellow', '⚠️');
    }

    public function success(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=green', '✅');
    }

    public function errorBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'ERROR', 'fg=white;bg=red', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }
    public function infoBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'INFO', 'fg=black;bg=cyan', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }

    public function successBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'OK', 'fg=white;bg=green;options=bold', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }

    public function warningBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'WARNING', 'fg=black;bg=yellow', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }

    public function noteBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'NOTE', 'fg=black;bg=yellow', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }

    public function cautionBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'CAUTION', 'fg=white;bg=red', ' ', true);
        foreach ($lines as $i => $line) {
            $this->text($line);
        }
    }

    public function listing(array $items, string $bullet = '●', int $indent = 4): void
    {
        $padding = str_repeat(' ', $indent);
        foreach ($items as $item) {
            $this->text("{$padding}<info>{$bullet}</> {$item}");
        }
    }

    public function text(string|array $message): void
    {
        if (is_array($message)) {
            foreach ($message as $line) {
                $line = StyleConverter::convertTags($line);
                WP_CLI::line(
                    WP_CLI::colorize($line)
                );
            }
        } else {
            $message = StyleConverter::convertTags($message);
            WP_CLI::line(
                WP_CLI::colorize($message)
            );
        }
    }

    private function comment(string|array $message): void {}

    /**
     * Tampilkan tabel (dengan header dan baris)
     * 
     * @param array $headers Header tabel index (misalnya: ['ID', 'Nama', 'Email'])
     * @param array $rows Baris tabel (misalnya: [[1, 'John Doe', 'john@example.com'], [2, 'Jane Doe', 'jane@example.com']])
     */
    public function table(array $headers, array $rows): void
    {

        $table = new Table();
        $headers = array_map(fn($item) => WP_CLI::colorize("%G{$item}%n"), $headers);
        $table->setAsciiPreColorized(true);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->display();
    }

    private function horizontalTable(array $headers, array $rows): void {}

    public function definitionList(...$items): void
    {
        foreach ($items as $item) {
            // 1. Jika itu Judul (String)
            if (is_string($item)) {
                $this->text("<fg=green;options=bold>{$item}</>");
                $this->hr('-', strlen($item));
            }

            // 3. Jika itu Data (Array)
            elseif (is_array($item)) {
                foreach ($item as $key => $value) {
                    $this->text("   <fg=cyan>{$key}</>:  <fg=yellow>{$value}</>");
                }
            }
        }
    }

    private function ask(string $question, ?string $default = null, ?callable $validator = null) {}

    private function askHidden(string $question, ?callable $validator = null) {}

    private function confirm(string $question, bool $default = true): bool
    {
        return false;
    }

    private function choice(string $question, array $choices, mixed $default = null, bool $multiSelect = false) {}

    private function progressStart(int $max = 0): void {}

    private function progressAdvance(int $step = 1): void {}

    private function progressFinish(): void {}

    private function createProgressBar(int $max = 0) {}

    private function progressIterate(iterable $iterable, ?int $max = null) {}

    private function askQuestion($question) {}

    public function writeln(string|iterable $messages): void
    {
        $this->write($messages, true);
    }

    public function write(string|iterable $messages, bool $newline = false): void
    {
        if (!is_iterable($messages)) {
            $messages = [$messages];
        }

        if (!$newline) {
            $message = implode(" ", $messages);
            $this->text($message);
            return;
        }

        foreach ($messages as $message) {
            $this->text($message);
        }
    }

    public function newLine(int $count = 1): void
    {
        WP_CLI::line(str_repeat(PHP_EOL, $count));
    }
    public function hr(string $border = '-', int $width = 50): void
    {
        WP_CLI::line(str_repeat($border, $width));
    }

    private function getErrorStyle() {}

    private function createTable() {}

    private function getProgressBar() {}

    private function tree(iterable $nodes, string $root = ''): void {}

    private function createTree(iterable $nodes, string $root = '') {}

    private function autoPrependBlock(): void {}

    private function autoPrependText(): void {}

    private function writeBuffer(string $message, bool $newLine, int $type): void {}

    private function createBlock(
        iterable $messages,
        ?string $type = null,
        ?string $style = null,
        string $prefix = ' ',
        bool $padding = false,
        bool $escape = false
    ): array {
        $indentLength = 0;
        $prefixLength = Helper::width($prefix);
        $lines = [];

        if (null !== $type) {
            $type = \sprintf('[%s] ', $type);
            $indentLength = Helper::width($type);
            $lineIndentation = str_repeat(' ', $indentLength);
        }

        // wrap and add newlines for each element
        $outputWrapper = new OutputWrapper();
        foreach ($messages as $key => $message) {

            $lines = array_merge(
                $lines,
                explode(\PHP_EOL, $outputWrapper->wrap(
                    $message,
                    $this->lineLength - $prefixLength - $indentLength,
                    \PHP_EOL
                ))
            );

            if (\count($messages) > 1 && $key < \count($messages) - 1) {
                // $lines[] = '';
            }
        }


        $firstLineIndex = 0;
        if ($padding) {
            $firstLineIndex = 1;
            array_unshift($lines, '');
            $lines[] = '';
        }

        foreach ($lines as $i => &$line) {
            if (null !== $type) {
                $line = $firstLineIndex === $i ? $type . $line : $lineIndentation . $line;
            }

            $line = $prefix . $line;
            $line .= str_repeat(' ', max($this->lineLength - Helper::width($line), 0));

            if ($style) {
                $line = \sprintf('<%s>%s</>', $style, $line);
            }
        }

        return $lines;
    }
}
