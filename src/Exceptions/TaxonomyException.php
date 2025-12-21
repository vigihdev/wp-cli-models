<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class TaxonomyException extends WpCliModelException
{
    public const NOT_FOUND = 4001;
    public const INVALID_TAXONOMY = 4002;
    public const ALREADY_EXISTS = 4003;
    public const CREATE_FAILED = 4004;
    public const UPDATE_FAILED = 4005;
    public const DELETE_FAILED = 4006;

    public static function notFound(string $taxonomy): self
    {
        return new self(
            message: sprintf("Taxonomy tidak ditemukan: %s", $taxonomy),
            context: ['taxonomy' => $taxonomy],
            code: self::NOT_FOUND,
            solutions: [
                'Periksa apakah taxonomy sudah terdaftar',
                'Gunakan wp taxonomy list untuk melihat daftar taxonomy yang tersedia'
            ]
        );
    }

    public static function invalidTaxonomy(string $taxonomy, array $registeredTaxonomies = []): self
    {
        $message = sprintf("Taxonomy tidak valid: %s", $taxonomy);
        $solutions = ['Periksa apakah taxonomy sudah terdaftar'];

        if (!empty($registeredTaxonomies)) {
            $message .= sprintf(". Taxonomy yang tersedia: %s", implode(', ', $registeredTaxonomies));
            $solutions[] = 'Gunakan salah satu taxonomy yang tersedia';
        }

        return new self(
            message: $message,
            context: ['taxonomy' => $taxonomy, 'registered_taxonomies' => implode(', ', $registeredTaxonomies)],
            code: self::INVALID_TAXONOMY,
            solutions: $solutions
        );
    }

    public static function alreadyExists(string $taxonomy): self
    {
        return new self(
            message: sprintf("Taxonomy sudah ada: %s", $taxonomy),
            context: ['taxonomy' => $taxonomy],
            code: self::ALREADY_EXISTS,
            solutions: [
                'Gunakan taxonomy yang berbeda',
                'Hapus taxonomy yang ada terlebih dahulu jika ingin membuat baru'
            ]
        );
    }

    public static function createFailed(string $taxonomy, string $error = ''): self
    {
        $message = sprintf("Gagal membuat taxonomy: %s", $taxonomy);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: ['taxonomy' => $taxonomy, 'error' => $error],
            code: self::CREATE_FAILED,
            solutions: [
                'Periksa permission user',
                'Periksa validitas data taxonomy'
            ]
        );
    }

    public static function updateFailed(string $taxonomy, string $error = ''): self
    {
        $message = sprintf("Gagal mengupdate taxonomy: %s", $taxonomy);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: ['taxonomy' => $taxonomy, 'error' => $error],
            code: self::UPDATE_FAILED,
            solutions: [
                'Periksa apakah taxonomy masih ada',
                'Periksa permission user'
            ]
        );
    }

    public static function deleteFailed(string $taxonomy, string $error = ''): self
    {
        $message = sprintf("Gagal menghapus taxonomy: %s", $taxonomy);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: ['taxonomy' => $taxonomy, 'error' => $error],
            code: self::DELETE_FAILED,
            solutions: [
                'Periksa apakah taxonomy sedang digunakan oleh terms',
                'Periksa permission user'
            ]
        );
    }
}
