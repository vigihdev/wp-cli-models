<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Helpers;

use Vigihdev\WpCliModels\Exceptions\ValidationException;

final class ValidationHelper
{
    /**
     * Validate required fields
     *
     * @param array $data Data to validate
     * @param array $requiredFields Required field names
     * @throws ValidationException
     */
    public static function validateRequired(array $data, array $requiredFields): void
    {
        $errors = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[$field] = sprintf('Field "%s" is required', $field);
            }
        }

        if (!empty($errors)) {
            throw ValidationException::fromErrors($errors);
        }
    }

    /**
     * Validate email
     *
     * @param string $email
     * @param string $fieldName
     * @throws ValidationException
     */
    public static function validateEmail(string $email, string $fieldName = 'email'): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::forField(
                $fieldName,
                sprintf('Invalid email format: %s', $email)
            );
        }
    }

    /**
     * Validate numeric range
     *
     * @param int|float $value
     * @param int|float $min
     * @param int|float $max
     * @param string $fieldName
     * @throws ValidationException
     */
    public static function validateRange(
        int|float $value,
        int|float $min,
        int|float $max,
        string $fieldName = 'value'
    ): void {
        if ($value < $min || $value > $max) {
            throw ValidationException::forField(
                $fieldName,
                sprintf('Value must be between %s and %s', $min, $max)
            );
        }
    }

    /**
     * Validate string length
     *
     * @param string $value
     * @param int $minLength
     * @param int $maxLength
     * @param string $fieldName
     * @throws ValidationException
     */
    public static function validateLength(
        string $value,
        int $minLength,
        int $maxLength,
        string $fieldName = 'value'
    ): void {
        $length = strlen(trim($value));

        if ($length < $minLength) {
            throw ValidationException::forField(
                $fieldName,
                sprintf('Minimum length is %d characters', $minLength)
            );
        }

        if ($length > $maxLength) {
            throw ValidationException::forField(
                $fieldName,
                sprintf('Maximum length is %d characters', $maxLength)
            );
        }
    }
}
