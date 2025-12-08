<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Formatters;

interface FormatterInterface
{
    public function save(): bool;
}
