<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args;


abstract class BaseArgsDto
{
    abstract public function toArray(): array;
    abstract public static function fromArray(array $data): static;
}
