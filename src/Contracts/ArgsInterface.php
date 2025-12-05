<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts;

/**
 * Interface ArgsInterface
 *
 * Interface dasar untuk semua argumen DTO
 */
interface ArgsInterface
{
    public function toArray(): array;
    public static function FromArray(array $data): static;
    public function validate(): void;
}
