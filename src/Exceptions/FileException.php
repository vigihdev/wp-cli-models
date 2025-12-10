<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

/**
 * Exception untuk semua file-related errors di WP-CLI context
 */
final class FileException extends \RuntimeException
{
    public function __construct(
        string $message,
        private string $filepath = '',
        private string $suggestion = '',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getFilePath(): string
    {
        return $this->filepath;
    }

    public function getSuggestion(): string
    {
        return $this->suggestion;
    }

    /**
     * Factory methods untuk common cases - SIMPLE VERSION
     */
    public static function notFound(string $filepath): self
    {
        return new self(
            sprintf("File tidak ditemukan: %s", basename($filepath)),
            $filepath,
            "Periksa path file dan pastikan file ada"
        );
    }

    public static function notReadable(string $filepath): self
    {
        return new self(
            "File tidak dapat dibaca",
            $filepath,
            "Periksa permission file: chmod +r " . basename($filepath)
        );
    }

    public static function notWritable(string $filepath): self
    {
        return new self(
            "File tidak dapat ditulis",
            $filepath,
            "Periksa permission file atau gunakan --overwrite"
        );
    }

    public static function invalidExtension(string $filepath, string $expected): self
    {
        $actual = pathinfo($filepath, PATHINFO_EXTENSION) ?: 'none';

        return new self(
            sprintf("File harus berekstensi .%s", $expected),
            $filepath,
            sprintf("Ekstensi saat ini: .%s", $actual)
        );
    }

    public static function invalidJson(string $filepath, string $error = ''): self
    {
        $message = "Format JSON tidak valid";
        if ($error) {
            $message .= ": " . $error;
        }

        return new self(
            $message,
            $filepath,
            "Validasi file menggunakan jsonlint.com"
        );
    }
}
