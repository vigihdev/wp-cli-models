<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;

final class InlinePreset
{

    private array $items = [];

    public function __construct(
        private readonly CliStyle $io,
    ) {}

    public function add(string $label, string $value, ?string $icon = null): self
    {
        $item = [
            'icon' => $icon,
            'label' => $label,
            'value' => $value
        ];
        $this->items = array_merge($this->items, [$item]);

        return $this;
    }

    private function summary(): void
    {
        $io = $this->io;
    }

    public function statistics(): void
    {
        $io = $this->io;
        $io->hr('-', 75);
        $items = [];
        foreach ($this->items as $i => $item) {
            $icon = (string)$item['icon'] ?? '';
            $label = $item['label'] ?? '';
            $value = $item['value'] ?? '';
            $items[] = sprintf("%s %s %s", $icon, $io->textGreen($label), $value);
        }
        $io->line(
            sprintf("%s %s", $io->textGreen('📊 Summary:', '%C'), implode(" | ", $items))
        );
        $io->hr('-', 75);
    }
}
