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
}
