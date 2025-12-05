<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts;

use Vigihdev\WpCliModels\Exceptions\ValidationException;

interface ValidatableInterface
{
    /**
     * Validate object data
     * @throws ValidationException
     */
    public function validate(): void;

    /**
     * Check if object is valid
     */
    public function isValid(): bool;
}
