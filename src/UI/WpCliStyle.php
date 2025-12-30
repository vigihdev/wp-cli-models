<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI;

use cli\Table;
use Vigihdev\WpCliModels\UI\Helper\Helper;
use Vigihdev\WpCliModels\UI\Helper\OutputWrapper;
use Vigihdev\WpCliModels\UI\Helper\StyleConverter;
use Vigihdev\WpCliModels\UI\Helper\SpinnerLoader;
use WP_CLI;

final class WpCliStyle
{

    public const MAX_LINE_LENGTH = 120;

    /**
     * The length of the line to use for text wrapping.
     */
    private int $lineLength;

    /**
     * The helper class for outputting messages.
     */
    private OutputWrapper $outputWrapper;

    /**
     * The helper class for loading spinners.
     */
    private SpinnerLoader $spinnerLoader;

    public function __construct()
    {
        $this->lineLength = min(80 - (int) (\DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);
    }

    /**
     * Displays a block of messages with an icon.
     *
     * @param string|array $message The message(s) to display.
     * @param string $style The style to apply to the block.
     * @param string $icon The icon to display before the message.
     * @return void
     */
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
    /**
     * Displays a title with a horizontal line under it.
     *
     * @param string $message The title message to display.
     * @param string $color The color to apply to the title.
     * @return void
     */
    public function title(string $message, string $color = 'fg=green;options=bold'): void
    {
        $this->text("<{$color}>{$message}</>");
        $this->hr('=', strlen($message));
        $this->newLine();
    }

    /**
     * Displays a section title with a horizontal line under it.
     *
     * @param string $message The section title message to display.
     * @param string $color The color to apply to the section title.
     * @return void
     */
    public function section(string $message, string $color = 'fg=yellow;options=bold'): void
    {

        $this->text("<{$color}>{$message}</>");
        $this->hr('=', strlen($message));
        $this->newLine();
    }

    /**
     * Displays a note message with a note icon.
     *
     * @param string|array $message The note message(s) to display.
     * @return void
     */
    public function note(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=yellow', '📝');
    }

    /**
     * Displays an error message with an error icon.
     *
     * @param string|array $message The error message(s) to display.
     * @return void
     */
    public function error(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=red', '❌');
    }

    /**
     * Displays a caution message with a caution icon.
     *
     * @param string|array $message The caution message(s) to display.
     * @return void
     */
    public function caution(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=red', '🔥');
    }

    /**
     * Displays an info message with an info icon.
     *
     * @param string|array $message The info message(s) to display.
     * @return void
     */
    public function info(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=cyan', 'ℹ️');
    }

    /**
     * Displays a warning message with a warning icon.
     *
     * @param string|array $message The warning message(s) to display.
     * @return void
     */
    public function warning(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=yellow', '⚠️');
    }

    /**
     * Displays a success message with a success icon.
     *
     * @param string|array $message The success message(s) to display.
     * @return void
     */
    public function success(string|array $message): void
    {
        $this->blockWithIcon($message, 'fg=green', '✅');
    }
    /**
     * Displays an error message block with an error icon.
     *
     * @param string|array $message The error message(s) to display.
     * @return void
     */
    public function errorBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'ERROR', 'fg=white;bg=red', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }
    /**
     * Displays an info message block with an info icon.
     *
     * @param string|array $message The info message(s) to display.
     * @return void
     */
    public function infoBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'INFO', 'fg=black;bg=cyan', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }
    /**
     * Displays a success message block with a success icon.
     *
     * @param string|array $message The success message(s) to display.
     * @return void
     */
    public function successBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'OK', 'fg=white;bg=green;options=bold', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }
    /**
     * Displays a warning message block with a warning icon.
     *
     * @param string|array $message The warning message(s) to display.
     * @return void
     */
    public function warningBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'WARNING', 'fg=black;bg=yellow', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }
    /**
     * Displays a note message block with a note icon.
     *
     * @param string|array $message The note message(s) to display.
     * @return void
     */
    public function noteBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'NOTE', 'fg=black;bg=yellow', ' ', true);
        foreach ($lines as $line) {
            $this->text($line);
        }
    }
    /**
     * Displays a caution message block with a caution icon.
     *
     * @param string|array $message The caution message(s) to display.
     * @return void
     */
    public function cautionBlock(string|array $message): void
    {
        $messages = is_array($message) ? $message : [$message];
        $lines = $this->createBlock($messages, 'CAUTION', 'fg=white;bg=red', ' ', true);
        foreach ($lines as $i => $line) {
            $this->text($line);
        }
    }

    /**
     * Displays a listing of items with a bullet point.
     *
     * @param array $items The items to display.
     * @param string $bullet The bullet point to use (default: '●').
     * @param int $indent The indentation level (default: 4).
     * @return void
     */
    public function listing(array $items, string $bullet = '●', int $indent = 4): void
    {
        $padding = str_repeat(' ', $indent);
        foreach ($items as $item) {
            $this->text("{$padding}<info>{$bullet}</> {$item}");
        }
    }

    /**
     * Displays a plain text message.
     *
     * @param string|array $message The message(s) to display.
     * @return void
     */
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
    public function spinnerStart(string $message): void
    {
        $spinner = new SpinnerLoader();
        $spinner->start($message);
        $this->spinnerLoader = $spinner;
    }

    public function spinnerStop(string $finalMessage): void
    {
        $this->spinnerLoader->stop($finalMessage);
    }

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

    public function write(string|iterable $messages, bool $newline = false, string $space = " "): void
    {
        if (!is_iterable($messages)) {
            $messages = [$messages];
        }

        if (!$newline) {
            $message = implode($space, $messages);
            $this->text($message);
            return;
        }

        foreach ($messages as $message) {
            $this->text($message);
        }
    }

    public function newLine(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            WP_CLI::line("");
        }
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
