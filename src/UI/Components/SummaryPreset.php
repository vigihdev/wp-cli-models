<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\Support\Text;
use Vigihdev\WpCliModels\UI\CliStyle;

final class SummaryPreset
{

    public function __construct(
        private readonly CliStyle $io,
        private readonly array $items,
    ) {}

    /**
     * Render sebagai array baris teks
     * 
     * @return void
     */
    public function render(): void
    {

        if (empty($this->items)) {
            return;
        }

        $title = "📊 Summary:";
        $io = $this->io;
        $io->hr();
        $io->line($io->textGreen($title));
        foreach ($this->renderItems() as $label => $value) {
            $padding = str_repeat(' ', strlen($label) - 6);
            $lineLabel = sprintf("%s%s", $padding, $label);
            $lineValue = sprintf("%s", (string)$value);
            $io->line(
                $io->textGreen($lineLabel, '%g') . $io->textGreen($lineValue)
            );
        }
        $io->hr();
    }

    /**
     * Render sebagai array baris teks
     *
     * @return array<string, string>
     */
    public function renderItems(): array
    {
        if (empty($this->items)) {
            return [];
        }

        // Cari panjang label terpanjang
        $maxLabelLength = max(array_map('strlen', array_keys($this->items)));

        $lines = [];
        foreach ($this->items as $label => $value) {
            if (empty($value) || !is_string($label)) {
                continue;
            }
            $label = Text::toTitleCase($label);
            $padding = str_repeat(' ', $maxLabelLength - strlen($label));
            $lines[sprintf("%s%s: ", $label, $padding)] = (string)$value;
        }

        return $lines;
    }
}
