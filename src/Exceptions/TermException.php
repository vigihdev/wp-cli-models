<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class TermException extends WpCliModelException
{
    public const NOT_FOUND = 3001;
    public const INVALID_TAXONOMY = 3002;
    public const ALREADY_EXISTS = 3003;
    public const CREATE_FAILED = 3004;
    public const UPDATE_FAILED = 3005;
    public const DELETE_FAILED = 3006;
    public const PARENT_NOT_FOUND = 3007;
    public const INVALID_PARENT = 3008;
    public const DUPLICATE_SLUG = 3009;

    public static function notFound(int $termId, ?string $taxonomy = null): self
    {
        $message = sprintf("Term tidak ditemukan dengan ID: %d", $termId);
        if ($taxonomy) {
            $message .= sprintf(" di taxonomy: %s", $taxonomy);
        }

        return new self(
            message: $message,
            context: [
                'term_id' => $termId,
                'taxonomy' => $taxonomy,
            ],
            code: self::NOT_FOUND,
            solutions: [
                'Periksa apakah term dengan ID tersebut ada',
                'Gunakan wp term list <taxonomy> untuk melihat daftar terms'
            ]
        );
    }

    public static function invalidTaxonomy(string $taxonomy, array $registeredTaxonomies = []): self
    {
        $message = sprintf("Taxonomy tidak valid: %s", $taxonomy);

        $solutions = ['Periksa apakah taxonomy sudah terdaftar'];
        if (!empty($registeredTaxonomies)) {
            $solutions[] = 'Taxonomy yang tersedia: ' . implode(', ', $registeredTaxonomies);
        }

        return new self(
            message: $message,
            context: [
                'taxonomy' => $taxonomy,
                'registered_taxonomies' => $registeredTaxonomies,
            ],
            code: self::INVALID_TAXONOMY,
            solutions: $solutions
        );
    }

    public static function alreadyExists(string $name, string $taxonomy): self
    {
        return new self(
            message: sprintf("Term '%s' sudah ada di taxonomy: %s", $name, $taxonomy),
            context: [
                'term_name' => $name,
                'taxonomy' => $taxonomy,
            ],
            code: self::ALREADY_EXISTS,
            solutions: [
                'Gunakan nama term yang berbeda',
                'Update term yang sudah ada'
            ]
        );
    }

    public static function createFailed(string $name, string $taxonomy, string $error = ''): self
    {
        $message = sprintf("Gagal membuat term '%s' di taxonomy: %s", $name, $taxonomy);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'term_name' => $name,
                'taxonomy' => $taxonomy,
                'error' => $error,
            ],
            code: self::CREATE_FAILED,
            solutions: [
                'Periksa permission user',
                'Pastikan taxonomy sudah terdaftar',
                'Periksa validitas data term'
            ]
        );
    }

    public static function updateFailed(int $termId, string $taxonomy, string $error = ''): self
    {
        $message = sprintf("Gagal mengupdate term ID: %d di taxonomy: %s", $termId, $taxonomy);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'term_id' => $termId,
                'taxonomy' => $taxonomy,
                'error' => $error,
            ],
            code: self::UPDATE_FAILED,
            solutions: [
                'Periksa apakah term masih ada',
                'Periksa permission user'
            ]
        );
    }

    public static function deleteFailed(int $termId, string $taxonomy, string $error = ''): self
    {
        $message = sprintf("Gagal menghapus term ID: %d di taxonomy: %s", $termId, $taxonomy);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'term_id' => $termId,
                'taxonomy' => $taxonomy,
                'error' => $error,
            ],
            code: self::DELETE_FAILED,
            solutions: [
                'Periksa apakah term sedang digunakan oleh posts',
                'Gunakan --force untuk menghapus term yang sedang digunakan',
                'Periksa permission user'
            ]
        );
    }

    public static function parentNotFound(int $parentId, string $taxonomy): self
    {
        return new self(
            message: sprintf("Parent term tidak ditemukan: ID %d di taxonomy: %s", $parentId, $taxonomy),
            context: [
                'parent_id' => $parentId,
                'taxonomy' => $taxonomy,
            ],
            code: self::PARENT_NOT_FOUND,
            solutions: [
                'Periksa apakah parent term masih ada',
                'Gunakan ID parent yang valid atau 0 untuk top-level term'
            ]
        );
    }

    public static function invalidParent(int $termId, int $parentId, string $taxonomy): self
    {
        return new self(
            message: sprintf(
                "Parent tidak valid: term ID %d tidak bisa menjadi parent dari term ID %d di taxonomy: %s",
                $parentId,
                $termId,
                $taxonomy
            ),
            context: [
                'term_id' => $termId,
                'parent_id' => $parentId,
                'taxonomy' => $taxonomy,
            ],
            code: self::INVALID_PARENT,
            solutions: [
                'Pastikan parent term bukan child dari term yang akan dibuat',
                'Hindari circular reference dalam term hierarchy',
                'Periksa apakah taxonomy mendukung hierarchical terms'
            ]
        );
    }

    public static function duplicateSlug(string $slug, string $taxonomy): self
    {
        return new self(
            message: sprintf("Slug '%s' sudah digunakan di taxonomy: %s", $slug, $taxonomy),
            context: [
                'slug' => $slug,
                'taxonomy' => $taxonomy,
            ],
            code: self::DUPLICATE_SLUG,
            solutions: [
                'Gunakan slug yang berbeda',
                'WordPress akan otomatis menambahkan suffix jika slug kosong'
            ]
        );
    }
}
