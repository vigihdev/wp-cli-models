<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI;

use cli\progress\Bar;
use cli\Table;
use Vigihdev\WpCliModels\UI\Components\{AskPreset, DryRunPresetExport, BlockPreset, DryRunPreset, SummaryPreset};
use WP_CLI;

final class CliStyle
{

    public function renderDryRunPreset(string $sectionName): DryRunPreset
    {
        return new DryRunPreset($this, $sectionName);
    }
    public function renderDryRunPresetExport(string $format, string $name, int $total, ?string $output = null): DryRunPresetExport
    {
        return new DryRunPresetExport(
            io: $this,
            format: $format,
            name: $name,
            total: $total,
            output: $output
        );
    }

    public function renderAsk(): AskPreset
    {
        return new AskPreset($this);
    }

    public function renderBlock(string $message): BlockPreset
    {
        return new BlockPreset($this, $message);
    }

    public function renderSummary(array $items, bool $withHr = true): void
    {
        $summary = new SummaryPreset($this, $items);
        $summary->render($withHr);
    }
    public function title(string $msg, string $color = '%G'): void
    {
        WP_CLI::log("");
        WP_CLI::log(
            WP_CLI::colorize("{$color}{$msg}%n")
        );
        WP_CLI::log(str_repeat("=", strlen($msg)));
        WP_CLI::log("");
    }

    public function line(string $message): void
    {
        WP_CLI::line($message);
    }

    public function success(string $msg): void
    {
        WP_CLI::success("✅ {$msg}");
    }

    public function warning(string $msg): void
    {
        WP_CLI::warning("⚠️ {$msg}");
    }

    public function error(string $msg, bool $exit = true): void
    {
        WP_CLI::error("❌ {$msg}", $exit);
    }

    public function separator(string $char = '─', int $length = 50, string $color = '%w'): void
    {
        WP_CLI::line(
            WP_CLI::colorize($color . str_repeat($char, $length) . '%n')
        );
    }

    public function summary(...$messages): void
    {
        WP_CLI::log("");
        WP_CLI::log("📊 {$this->textGreen("Summary:")}");
        WP_CLI::log("");
        foreach ($messages as $message) {
            WP_CLI::log("{$this->textGreen($message, '%g')}");
        }
        WP_CLI::log("");
    }

    public function listLabel(array $items, array $fields): void
    {
        foreach ($fields as $i => $label) {
            if (isset($items[$i])) {
                WP_CLI::log(
                    sprintf("🏮 %s : %s", $this->highlightText((string) $label), $this->textGreen((string) $items[$i], '%g'))
                );
            }
        }
    }

    public function logFatal(...$messages): void
    {
        WP_CLI::line(
            sprintf("%s ❌ %s", WP_CLI::colorize("%R[FATAL]%n"), implode(' ', $messages))
        );
    }

    public function logInfo(...$messages): void
    {
        WP_CLI::line(
            sprintf("%s ℹ️   %s", WP_CLI::colorize("%B[INFO]%n"), implode(' ', $messages))
        );
    }

    public function logWarning(...$messages): void
    {
        WP_CLI::line(
            sprintf("%s ⚠️  %s", WP_CLI::colorize("%Y[WARNING]%n"), implode(' ', $messages))
        );
    }

    public function logError(...$messages): void
    {
        WP_CLI::line(
            sprintf("%s ❌ %s", WP_CLI::colorize("%M[ERROR]%n"), implode(' ', $messages))
        );
    }

    public function textGreen(string $message, string $color = '%G'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }

    public function textYellow(string $message, string $color = '%Y'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }

    public function textMagenta(string $message, string $color = '%P'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }

    public function textRed(string $message, string $color = '%R'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }
    public function textBlue(string $message, string $color = '%b'): string
    {
        return WP_CLI::colorize("{$color}{$message}%n");
    }

    public function textSuccess(string $message): string
    {
        return WP_CLI::colorize("%g{$message}%n");
    }

    public function textError(string $message): string
    {
        return WP_CLI::colorize("%r{$message}%n");
    }

    public function textInfo(string $message): string
    {
        return WP_CLI::colorize("%b{$message}%n");
    }
    public function textWarning(string $message): string
    {
        return WP_CLI::colorize("%y{$message}%n");
    }

    public function highlightText(string $message): string
    {
        return WP_CLI::colorize("%C{$message}%n");
    }


    public function start(string $message)
    {
        WP_CLI::line("🚀 {$message}");
        WP_CLI::log('');
    }


    /**
     * @param string[] $fields
     * @param array<int,string[]> $items 
     */
    public function table(array $items, array $fields)
    {
        $fields = array_map(function ($v) {
            $v = ucfirst($v);
            return WP_CLI::colorize("%G{$v}%n");
        }, $fields);
        $table = new Table();
        $table->setHeaders($fields);
        $table->setRows($items);

        $table->display();
    }

    /**
     * Tampilkan catatan/note (info dengan format khusus)
     */
    public function note(string $message): void
    {
        WP_CLI::log("");
        WP_CLI::log(
            WP_CLI::colorize("%C[NOTE]%n $message")
        );
        WP_CLI::log("");
    }

    /**
     * Tampilkan info message
     */
    public function info(string $message): void
    {
        WP_CLI::log(
            WP_CLI::colorize("%b[INFO]%n {$message}")
        );
    }

    /**
     * Tampilkan comment/komentar
     */
    public function comment(string $message): void
    {
        WP_CLI::log(
            WP_CLI::colorize("%Y[COMMENT]%n %w{$message}%n")
        );
    }

    /**
     * Tampilkan caution/perhatian
     */
    public function caution(string $message): void
    {
        WP_CLI::log("");
        WP_CLI::log(
            WP_CLI::colorize("%Y[CAUTION]%n %R{$message}%n")
        );
        WP_CLI::log("");
    }

    /**
     * Tampilkan progress bar (simple version)
     */
    public function progressBar(int $total, string $format = null): Bar
    {
        $format = $format ?? 'Progress: %percent%%';
        $bar = new Bar($format, $total);
        return $bar;
    }

    /**
     * Buat progress bar instance
     */
    public function createProgressBar(int $max = 0): Bar
    {
        return new Bar('Progress', $max, 0.1);
    }

    /**
     * Tampilkan listing/daftar
     */
    public function listing(array $items): void
    {
        WP_CLI::log("");
        foreach ($items as $index => $item) {
            WP_CLI::log(sprintf("  %d. %s", $index + 1, $item));
        }
        WP_CLI::log("");
    }

    /**
     * Tampilkan text dengan newline
     */
    public function text(string $message): void
    {
        WP_CLI::log($message);
    }

    /**
     * Tampilkan new line
     */
    public function newLine(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            WP_CLI::log("");
        }
    }

    /**
     * Tampilkan block text dengan background
     */
    public function block(string $message, string $type = 'info', bool $padding = true): void
    {
        $colors = [
            'info' => '%B',
            'success' => '%G',
            'warning' => '%Y',
            'error' => '%R',
            'note' => '%C',
        ];

        $color = $colors[$type] ?? '%w';

        if ($padding) {
            WP_CLI::log("");
        }

        $lines = explode("\n", $message);
        foreach ($lines as $line) {
            WP_CLI::log(
                WP_CLI::colorize("{$color}  {$line}%n")
            );
        }

        if ($padding) {
            WP_CLI::log("");
        }
    }

    /**
     * Log biasa (alias untuk WP_CLI::log)
     */
    public function log(string $message): void
    {
        WP_CLI::log($message);
    }

    /**
     * Error log tanpa exit
     */
    public function errorLog(string $message): void
    {
        WP_CLI::error($message, false);
    }

    /**
     * Tampilkan key-value pairs
     */
    public function definitionList(array $items, bool $hr = false): void
    {
        if ($hr) {
            $this->hr();
        }

        // Cari key terpanjang untuk alignment
        $maxLength = 0;
        foreach (array_keys($items) as $key) {
            $length = strlen($key);
            if ($length > $maxLength) {
                $maxLength = $length;
            }
        }

        foreach ($items as $key => $value) {
            $spaces = str_repeat(' ', $maxLength - strlen($key));
            WP_CLI::log(
                WP_CLI::colorize("%G{$key}:%n{$spaces} {$value}")
            );
        }
        if ($hr) {
            $this->hr();
        }
    }

    /**
     * Tampilkan horizontal rule
     */
    public function hr(string $char = '─', int $length = 50): void
    {
        $this->separator($char, $length);
    }

    /**
     * Tampilkan success dengan icon
     */
    public function successWithIcon(string $message, string $icon = '✅'): void
    {
        WP_CLI::success("{$icon} {$message}");
    }

    /**
     * Tampilkan warning dengan icon
     */
    public function warningWithIcon(string $message, string $icon = '⚠️'): void
    {
        WP_CLI::warning("{$icon}  {$message}");
    }

    /**
     * Tampilkan error dengan icon
     */
    public function errorWithIcon(string $message, string $icon = '❌', bool $exit = true): void
    {
        WP_CLI::error("{$icon} {$message}", $exit);
    }
}
