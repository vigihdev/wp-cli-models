<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs;

use Vigihdev\WpCliModels\Contracts\ArrayAbleInterface;
use Vigihdev\WpCliModels\Contracts\ValidatableInterface;
use Vigihdev\WpCliModels\Exceptions\ValidationException;

abstract class BaseArgsDto implements ArrayAbleInterface, ValidatableInterface
{
    protected array $errors = [];

    public function isValid(): bool
    {
        try {
            $this->validate();
            return true;
        } catch (ValidationException) {
            return false;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    abstract public function validate(): void;
    abstract public function toArray(): array;
}
