<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class JsonImportException extends WpCliModelException
{
    public const FILE_NOT_FOUND = 3001;
    public const FILE_NOT_READABLE = 3002;
    public const INVALID_JSON = 3003;
    public const INVALID_SCHEMA = 3004;
    public const MISSING_REQUIRED_FIELD = 3005;
    public const INVALID_DATA_TYPE = 3006;

    private ?string $filePath;
    private ?array $validationErrors;

    public function __construct(
        string $message,
        ?string $filePath = null,
        ?array $validationErrors = null,
        array $context = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->filePath = $filePath;
        $this->validationErrors = $validationErrors;

        $context['file_path'] = $filePath;
        $context['validation_errors'] = $validationErrors;

        parent::__construct($message, $context, $code, $previous);
    }

    public static function fileNotFound(string $filePath): self
    {
        return new self(
            sprintf("File tidak ditemukan: %s", $filePath),
            $filePath,
            null,
            [],
            self::FILE_NOT_FOUND
        );
    }

    public static function fileNotReadable(string $filePath): self
    {
        return new self(
            sprintf("File tidak dapat dibaca: %s", $filePath),
            $filePath,
            null,
            [],
            self::FILE_NOT_READABLE
        );
    }

    public static function invalidJson(string $filePath, string $jsonError): self
    {
        return new self(
            sprintf("File JSON tidak valid: %s. Error: %s", $filePath, $jsonError),
            $filePath,
            null,
            ['json_error' => $jsonError],
            self::INVALID_JSON
        );
    }

    public static function invalidSchema(string $filePath, array $validationErrors): self
    {
        return new self(
            sprintf("Data JSON tidak sesuai schema. File: %s", $filePath),
            $filePath,
            $validationErrors,
            [],
            self::INVALID_SCHEMA
        );
    }

    public static function missingRequiredField(string $filePath, string $field): self
    {
        return new self(
            sprintf("Field wajib '%s' tidak ditemukan di file: %s", $field, $filePath),
            $filePath,
            null,
            ['missing_field' => $field],
            self::MISSING_REQUIRED_FIELD
        );
    }

    public static function invalidDataType(string $filePath, string $field, string $expectedType): self
    {
        return new self(
            sprintf("Field '%s' harus bertipe %s. File: %s", $field, $expectedType, $filePath),
            $filePath,
            null,
            [
                'field' => $field,
                'expected_type' => $expectedType
            ],
            self::INVALID_DATA_TYPE
        );
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function getValidationErrors(): ?array
    {
        return $this->validationErrors;
    }
}
