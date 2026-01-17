<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Entities;

abstract class BaseEntityDto
{
    abstract public function toArray(): array;

    abstract public static function fromQuery(mixed $data): static;

    abstract public static function fromArray(array $data): static;
}
