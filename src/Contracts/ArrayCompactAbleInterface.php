<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts;


interface ArrayCompactAbleInterface
{
    public function toArray(): array;
    public static function FromArray(array $data): static;
}
