<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;

final class ConsequencePreset
{
    public function __construct(
        private readonly CliStyle $io
    ) {}
}
