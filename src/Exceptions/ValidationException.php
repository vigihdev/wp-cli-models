<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

/**
 * Exception untuk menangani error validasi data
 * 
 * Digunakan ketika data tidak memenuhi validation rules
 * dalam DTOs, Entities, atau Services.
 */
class ValidationException extends \InvalidArgumentException
{
    /**
     * @var array<string, string|array> Error details
     */
    private array $errors = [];

    /**
     * @var string|null Field yang menyebabkan error
     */
    private ?string $field = null;

    /**
     * Constructor dengan support untuk error details
     *
     * @param string $message Pesan error
     * @param array<string, string|array> $errors Detail error per field
     * @param string|null $field Nama field yang error
     * @param int $code Error code
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct(
        string $message = 'Validation failed',
        array $errors = [],
        ?string $field = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
        $this->field = $field;
    }

    /**
     * Mendapatkan detail error
     *
     * @return array<string, string|array>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Mendapatkan error untuk field tertentu
     *
     * @param string $field Nama field
     * @return string|array|null Error message atau null jika tidak ada
     */
    public function getError(string $field): string|array|null
    {
        return $this->errors[$field] ?? null;
    }

    /**
     * Mendapatkan field yang menyebabkan error
     *
     * @return string|null
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * Mengecek apakah ada error untuk field tertentu
     *
     * @param string $field Nama field
     * @return bool
     */
    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /**
     * Menambahkan error baru
     *
     * @param string $field Nama field
     * @param string|array $message Pesan error
     * @return self
     */
    public function addError(string $field, string|array $message): self
    {
        $this->errors[$field] = $message;
        return $this;
    }

    /**
     * Mengecek apakah ada error sama sekali
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Mendapatkan jumlah error
     *
     * @return int
     */
    public function countErrors(): int
    {
        return count($this->errors);
    }

    /**
     * Mendapatkan semua pesan error dalam format flat array
     *
     * @return array<string> Array pesan error
     */
    public function getErrorMessages(): array
    {
        $messages = [];

        foreach ($this->errors as $error) {
            if (is_array($error)) {
                $messages = array_merge($messages, $error);
            } else {
                $messages[] = (string) $error;
            }
        }

        return $messages;
    }

    /**
     * Mendapatkan semua field yang memiliki error
     *
     * @return array<string> Nama-nama field yang error
     */
    public function getErrorFields(): array
    {
        return array_keys($this->errors);
    }

    /**
     * Membuat ValidationException dari single error
     *
     * @param string $field Nama field
     * @param string $message Pesan error
     * @return self
     */
    public static function forField(string $field, string $message): self
    {
        return new self(
            message: sprintf('Validation failed for field "%s": %s', $field, $message),
            errors: [$field => $message],
            field: $field
        );
    }

    /**
     * Membuat ValidationException dari multiple errors
     *
     * @param array<string, string|array> $errors Array error [field => message]
     * @return self
     */
    public static function fromErrors(array $errors): self
    {
        return new self(
            message: sprintf('Validation failed with %d error(s)', count($errors)),
            errors: $errors
        );
    }

    /**
     * Convert exception to array untuk response/serialization
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'errors' => $this->errors,
            'field' => $this->field,
            'error_count' => $this->countErrors(),
            'error_fields' => $this->getErrorFields(),
        ];
    }

    /**
     * Convert exception ke format untuk WP-CLI
     *
     * @return array<string, mixed>
     */
    public function toWpCliFormat(): array
    {
        return [
            'error' => $this->getMessage(),
            'details' => $this->errors,
        ];
    }

    /**
     * Mendapatkan string representation untuk logging
     *
     * @return string
     */
    public function __toString(): string
    {
        $string = sprintf(
            "%s: %s\n",
            static::class,
            $this->getMessage()
        );

        if ($this->hasErrors()) {
            $string .= "Validation Errors:\n";
            foreach ($this->errors as $field => $error) {
                if (is_array($error)) {
                    $string .= sprintf("  %s: %s\n", $field, implode(', ', $error));
                } else {
                    $string .= sprintf("  %s: %s\n", $field, $error);
                }
            }
        }

        if ($this->getPrevious()) {
            $string .= sprintf(
                "Previous: %s\n",
                $this->getPrevious()->getMessage()
            );
        }

        return $string;
    }
}
