<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

final class DefinitionList
{
    /**
     * @var array<string, string>
     */
    private array $items = [];

    public function add(string $label, string $value): self
    {
        $this->items[$label] = $value;
        return $this;
    }

    /**
     * Render sebagai array baris teks
     *
     * @return string[]
     */
    public function render(): array
    {
        if (empty($this->items)) {
            return [];
        }

        // Cari panjang label terpanjang
        $maxLabelLength = max(array_map('strlen', array_keys($this->items)));

        $lines = [];
        foreach ($this->items as $label => $value) {
            $padding = str_repeat(' ', $maxLabelLength - strlen($label));
            $lines[] = sprintf("%s%s: %s", $label, $padding, $value);
        }

        return $lines;
    }
}
