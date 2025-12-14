<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

/**
 * Exception untuk semua file-related errors di WP-CLI context
 */
final class FileException extends WpCliModelException
{
    public const NOT_FOUND = 4001;
    public const NOT_READABLE = 4002;
    public const NOT_WRITABLE = 4003;
    public const INVALID_EXTENSION = 4004;
    public const INVALID_JSON = 4005;
    public const INVALID_XML = 4006;
    public const INVALID_CSV = 4007;
    public const FILE_TOO_LARGE = 4008;
    public const EMPTY_FILE = 4009;

    public static function notFound(string $filepath): self
    {
        return new self(
            message: sprintf("File tidak ditemukan: %s", basename($filepath)),
            context: [
                'filepath' => $filepath,
            ],
            code: self::NOT_FOUND,
            solutions: [
                'Periksa path file dan pastikan file ada',
                'Periksa izin tulis (chmod/chown)'
            ]
        );
    }

    public static function notReadable(string $filepath): self
    {
        return new self(
            message: "File tidak dapat dibaca",
            context: [
                'filepath' => $filepath,
            ],
            code: self::NOT_READABLE,
            solutions: [
                'Periksa permission file: chmod +r ' . basename($filepath)
            ]
        );
    }

    public static function notWritable(string $filepath): self
    {
        return new self(
            message: "File tidak dapat ditulis",
            context: [
                'filepath' => $filepath,
            ],
            code: self::NOT_WRITABLE,
            solutions: [
                'Periksa permission file atau gunakan --overwrite',
                'Periksa permission direktori parent'
            ]
        );
    }

    public static function invalidExtension(string $filepath, string $expected): self
    {
        $actual = pathinfo($filepath, PATHINFO_EXTENSION) ?: 'none';

        return new self(
            message: sprintf("File harus berekstensi .%s", $expected),
            context: [
                'filepath' => $filepath,
                'expected_extension' => $expected,
                'actual_extension' => $actual,
            ],
            code: self::INVALID_EXTENSION,
            solutions: [
                sprintf("Ekstensi saat ini: .%s", $actual),
                'Ubah ekstensi file atau gunakan file yang sesuai'
            ]
        );
    }

    public static function invalidJson(string $filepath, ?string $error = null): self
    {
        $message = "Format JSON tidak valid";
        if ($error) {
            $message .= ": " . $error;
        }

        return new self(
            message: $message,
            context: [
                'filepath' => $filepath,
                'error' => $error,
            ],
            code: self::INVALID_JSON,
            solutions: [
                'Validasi file menggunakan jsonlint.com',
                'Periksa syntax JSON (koma, kurung, quotes)'
            ]
        );
    }

    public static function invalidXml(string $filepath, ?string $error = null): self
    {
        $message = "Format XML tidak valid";
        if ($error) {
            $message .= ": " . $error;
        }

        return new self(
            message: $message,
            context: [
                'filepath' => $filepath,
                'error' => $error,
            ],
            code: self::INVALID_XML,
            solutions: [
                'Validasi file XML menggunakan XML validator',
                'Periksa tag pembuka dan penutup'
            ]
        );
    }

    public static function invalidCsv(string $filepath, ?string $error = null): self
    {
        $message = "Format CSV tidak valid";
        if ($error) {
            $message .= ": " . $error;
        }

        return new self(
            message: $message,
            context: [
                'filepath' => $filepath,
                'error' => $error,
            ],
            code: self::INVALID_CSV,
            solutions: [
                'Periksa delimiter dan format CSV',
                'Pastikan jumlah kolom konsisten'
            ]
        );
    }

    public static function fileTooLarge(string $filepath, int $maxSize, int $actualSize): self
    {
        return new self(
            message: sprintf(
                'File terlalu besar: %s (maksimal %s)',
                size_format($actualSize),
                size_format($maxSize)
            ),
            context: [
                'filepath' => $filepath,
                'max_size' => $maxSize,
                'actual_size' => $actualSize,
            ],
            code: self::FILE_TOO_LARGE,
            solutions: [
                'Kompres file atau gunakan file yang lebih kecil',
                'Tingkatkan batas ukuran file jika memungkinkan'
            ]
        );
    }

    public static function emptyFile(string $filepath): self
    {
        return new self(
            message: 'File kosong atau tidak memiliki konten',
            context: [
                'filepath' => $filepath,
            ],
            code: self::EMPTY_FILE,
            solutions: [
                'Pastikan file memiliki konten yang valid',
                'Periksa proses pembuatan file'
            ]
        );
    }
}
