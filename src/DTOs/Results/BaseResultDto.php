<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Results;

abstract class BaseResultDto
{
    abstract public function toArray(): array;
}
