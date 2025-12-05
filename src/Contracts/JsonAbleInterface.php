<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts;


interface JsonAbleInterface
{
    public function toJson(): string;
}
