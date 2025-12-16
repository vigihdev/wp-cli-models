<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Themes;

use Vigihdev\WpCliModels\UI\CliStyle;

final class MinimalTheme
{
    public function __construct(
        private readonly CliStyle $io,
        private readonly string $section,
    ) {}

    private function renderTitle(): void {}
    private function dryRun(): void {}
    private function viewDetail(array $items, array $fields): void {}
}
