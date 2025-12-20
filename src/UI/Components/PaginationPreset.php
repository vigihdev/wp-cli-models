<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;

final class PaginationPreset
{
    public function __construct(
        private readonly CliStyle $io,
        private readonly string $sectionName,
        private readonly int $showItem,
        private readonly int $total,
    ) {}

    public function render(): void
    {
        $io = $this->io;
        $message = sprintf(
            '🔍 Showing items %s %s of %s',
            $this->sectionName,
            $io->textGreen((string)$this->showItem),
            $io->textGreen((string)$this->total),
        );
        $hrWidth = strlen(preg_replace('/\x1b\[[0-9;]*[a-zA-Z]/', '', $message)) + 10;
        $io->newLine();
        $io->hr('-', $hrWidth);
        $io->line($message);
        $io->hr('-', $hrWidth);
    }
}
