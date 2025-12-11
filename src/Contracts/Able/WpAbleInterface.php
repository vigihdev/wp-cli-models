<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Able;


interface WpAbleInterface
{
    public function toWpFormat(): array;
}
