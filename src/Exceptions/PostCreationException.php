<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

use WP_Error;

/**
 * Exception untuk post creation/update failures
 */
final class PostCreationException extends WpCliModelException
{
    public const CODE_INSERT_FAILED = 3001;
    public const CODE_UPDATE_FAILED = 3002;
    public const CODE_POST_NOT_FOUND = 3003;
    public const CODE_INVALID_AUTHOR = 3004;
    public const CODE_INVALID_STATUS = 3005;

    /**
     * Create dari WP_Error
     */
    public static function fromWpError(WP_Error $error, array $context = []): self
    {
        return new self(
            message: $error->get_error_message(),
            context: array_merge([
                'wp_error_data' => $error->get_error_data(),
                'wp_error_codes' => $error->get_error_codes(),
            ], $context),
            code: (int) $error->get_error_code()
        );
    }

    public static function insertFailed(array $postData, ?string $error = null): self
    {
        $message = 'Failed to insert post';
        if ($error) {
            $message .= ": {$error}";
        }

        // Remove sensitive data dari context
        $safePostData = array_filter($postData, function ($key) {
            return !in_array($key, ['post_password', 'post_author_email']);
        }, ARRAY_FILTER_USE_KEY);

        return new self(
            message: $message,
            context: ['post_data' => $safePostData],
            code: self::CODE_INSERT_FAILED,
            solutions: [
                'Periksa data post yang dikirim',
                'Pastikan format data sesuai dengan requirement'
            ]
        );
    }

    public static function updateFailed(int $postId, array $postData, ?string $error = null): self
    {
        $message = 'Failed to update post';
        if ($error) {
            $message .= ": {$error}";
        }

        // Remove sensitive data dari context
        $safePostData = array_filter($postData, function ($key) {
            return !in_array($key, ['post_password', 'post_author_email']);
        }, ARRAY_FILTER_USE_KEY);

        return new self(
            message: $message,
            context: ['post_id' => $postId, 'post_data' => $safePostData],
            code: self::CODE_UPDATE_FAILED,
            solutions: [
                'Periksa apakah post dengan ID tersebut tersedia',
                'Pastikan data yang dikirim sesuai format'
            ]
        );
    }

    public static function postNotFound(int $postId): self
    {
        return new self(
            message: "Post with ID {$postId} not found",
            context: ['post_id' => $postId],
            code: self::CODE_POST_NOT_FOUND,
            solutions: [
                'Periksa kembali ID post',
                'Pastikan post belum dihapus'
            ]
        );
    }

    public static function invalidAuthor(int $authorId): self
    {
        return new self(
            message: "Invalid author ID: {$authorId}",
            context: ['author_id' => $authorId],
            code: self::CODE_INVALID_AUTHOR,
            solutions: [
                'Periksa kembali ID author',
                'Pastikan user dengan ID tersebut tersedia'
            ]
        );
    }

    public static function invalidStatus(string $status): self
    {
        return new self(
            message: "Invalid post status: {$status}",
            context: ['status' => $status],
            code: self::CODE_INVALID_STATUS,
            solutions: [
                'Gunakan status post yang valid',
                'Periksa dokumentasi untuk daftar status yang didukung'
            ]
        );
    }

    public static function duplicatePostTitle(string $title, string $type = 'post', ?string $error = null): self
    {
        $message = "Failed to create {$type} post '{$title}'";
        if ($error) {
            $message .= ": {$error}";
        }

        return new self(
            message: $message,
            context: ['title' => $title, 'type' => $type, 'error' => $error],
            code: self::CODE_INSERT_FAILED,
            solutions: [
                'Gunakan judul yang berbeda',
                'Periksa apakah post dengan judul ini sudah ada',
                'Tambahkan sufiks atau prefiks untuk membedakan'
            ]
        );
    }
}
