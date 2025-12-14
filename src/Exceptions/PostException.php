<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class PostException extends WpCliModelException
{
    public static function notFound(int $postId): self
    {
        return new self(
            message: sprintf("Post dengan ID %d tidak ditemukan", $postId),
            context: ['post_id' => $postId],
            solutions: [
                "Periksa kembali ID post",
                "Pastikan post belum dihapus"
            ]
        );
    }

    public static function createFailed(string $title, string $postType, string $error = ''): self
    {
        return new self(
            message: sprintf("Gagal membuat post '%s' (tipe: %s)", $title, $postType),
            context: [
                'title' => $title,
                'post_type' => $postType,
                'error' => $error
            ],
            solutions: [
                "Periksa data yang dikirim",
                "Periksa error: " . ($error ?: 'tidak diketahui')
            ]
        );
    }

    public static function invalidPostType(string $postType, array $allowedTypes): self
    {
        return new self(
            message: sprintf("Tipe post %s tidak valid", $postType),
            context: [
                'post_type' => $postType,
                'allowed_types' => $allowedTypes
            ],
            solutions: [
                "Gunakan salah satu tipe yang valid: " . implode(', ', $allowedTypes)
            ]
        );
    }

    public static function updateFailed(int $postId, string $error = ''): self
    {
        return new self(
            message: sprintf("Gagal mengupdate post ID %d", $postId),
            context: ['post_id' => $postId, 'error' => $error],
            solutions: [
                "Periksa data yang dikirim",
                "Periksa error: " . ($error ?: 'tidak diketahui')
            ]
        );
    }

    public static function deleteFailed(int $postId, string $error = ''): self
    {
        return new self(
            message: sprintf("Gagal menghapus post ID %d", $postId),
            context: ['post_id' => $postId, 'error' => $error],
            solutions: [
                "Pastikan post masih ada",
                "Periksa error: " . ($error ?: 'tidak diketahui')
            ]
        );
    }

    public static function invalidStatus(string $status, array $allowedStatuses): self
    {
        return new self(
            message: sprintf("Status post '%s' tidak valid", $status),
            context: ['status' => $status, 'allowed_statuses' => $allowedStatuses],
            solutions: [
                "Gunakan salah satu status yang valid: " . implode(', ', $allowedStatuses)
            ]
        );
    }

    public static function metaUpdateFailed(int $postId, string $metaKey): self
    {
        return new self(
            message: sprintf("Gagal mengupdate meta '%s' untuk post ID %d", $metaKey, $postId),
            context: ['post_id' => $postId, 'meta_key' => $metaKey],
            solutions: [
                "Periksa koneksi database",
                "Pastikan meta key valid"
            ]
        );
    }
}
