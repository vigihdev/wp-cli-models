<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class DirectoryException extends WpCliModelException
{
    public const NOT_FOUND = 5001;
    public const NOT_READABLE = 5002;
    public const NOT_WRITABLE = 5003;
    public const ALREADY_EXISTS = 5004;
    public const NOT_EMPTY = 5005;
    public const CREATE_FAILED = 5006;
    public const DELETE_FAILED = 5007;
    public const INVALID_PATH = 5008;

    public static function notFound(string $dirpath): self
    {
        return new self(
            message: sprintf("Direktori tidak ditemukan: %s", $dirpath),
            context: [
                'dirpath' => $dirpath,
            ],
            code: self::NOT_FOUND,
            solutions: [
                'Periksa path direktori dan pastikan direktori ada',
                'Buat direktori terlebih dahulu jika belum ada'
            ]
        );
    }

    public static function notReadable(string $dirpath): self
    {
        return new self(
            message: sprintf("Direktori tidak dapat dibaca: %s", $dirpath),
            context: [
                'dirpath' => $dirpath,
            ],
            code: self::NOT_READABLE,
            solutions: [
                'Periksa permission direktori: chmod +r ' . basename($dirpath),
                'Periksa ownership direktori'
            ]
        );
    }

    public static function notWritable(string $dirpath): self
    {
        return new self(
            message: sprintf("Direktori tidak dapat ditulis: %s", $dirpath),
            context: [
                'dirpath' => $dirpath,
            ],
            code: self::NOT_WRITABLE,
            solutions: [
                'Periksa permission direktori: chmod +w ' . basename($dirpath),
                'Periksa ownership direktori'
            ]
        );
    }

    public static function alreadyExists(string $dirpath): self
    {
        return new self(
            message: sprintf("Direktori sudah ada: %s", $dirpath),
            context: [
                'dirpath' => $dirpath,
            ],
            code: self::ALREADY_EXISTS,
            solutions: [
                'Gunakan path direktori yang berbeda',
                'Hapus direktori yang ada terlebih dahulu',
                'Gunakan flag --overwrite jika tersedia'
            ]
        );
    }

    public static function notEmpty(string $dirpath, int $fileCount = 0): self
    {
        $message = sprintf("Direktori tidak kosong: %s", $dirpath);
        if ($fileCount > 0) {
            $message .= sprintf(" (%d file/folder)", $fileCount);
        }

        return new self(
            message: $message,
            context: [
                'dirpath' => $dirpath,
                'file_count' => $fileCount,
            ],
            code: self::NOT_EMPTY,
            solutions: [
                'Kosongkan direktori terlebih dahulu',
                'Gunakan flag --force untuk menghapus direktori beserta isinya'
            ]
        );
    }

    public static function createFailed(string $dirpath, string $error = ''): self
    {
        $message = sprintf("Gagal membuat direktori: %s", $dirpath);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'dirpath' => $dirpath,
                'error' => $error,
            ],
            code: self::CREATE_FAILED,
            solutions: [
                'Periksa permission direktori parent',
                'Periksa apakah path valid',
                'Periksa disk space'
            ]
        );
    }

    public static function deleteFailed(string $dirpath, string $error = ''): self
    {
        $message = sprintf("Gagal menghapus direktori: %s", $dirpath);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'dirpath' => $dirpath,
                'error' => $error,
            ],
            code: self::DELETE_FAILED,
            solutions: [
                'Periksa permission direktori',
                'Pastikan direktori tidak sedang digunakan',
                'Kosongkan direktori terlebih dahulu'
            ]
        );
    }

    public static function invalidPath(string $dirpath, string $reason = ''): self
    {
        $message = sprintf("Path direktori tidak valid: %s", $dirpath);
        if ($reason) {
            $message .= ". " . $reason;
        }

        return new self(
            message: $message,
            context: [
                'dirpath' => $dirpath,
                'reason' => $reason,
            ],
            code: self::INVALID_PATH,
            solutions: [
                'Gunakan path absolut atau relatif yang valid',
                'Hindari karakter khusus dalam path',
                'Periksa panjang path (maksimal 255 karakter)'
            ]
        );
    }
}
