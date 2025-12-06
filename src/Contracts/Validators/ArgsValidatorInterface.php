<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Validators;

interface ArgsValidatorInterface
{

    /**
     * @throws ValidationException
     */
    public function validate(object $dto): void;


    /**
     * @param string $field
     * @param mixed $value
     * @throws ValidationException
     */
    public static function validateField(string $field, mixed $value): void;
}
