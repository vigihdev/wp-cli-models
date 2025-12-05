<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts;


interface FromArrayAbleInterface
{
    public static function FromArray(array $data): static;
}
