<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class PostException extends WpCliModelException
{
    public const NOT_FOUND = 4001;
    public const CREATE_FAILED = 4002;
    public const UPDATE_FAILED = 4003;
    public const DELETE_FAILED = 4004;
    public const INVALID_POST_TYPE = 4005;

    private ?int $postId;
    private ?string $postType;

    public function __construct(
        string $message,
        ?int $postId = null,
        ?string $postType = null,
        array $context = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->postId = $postId;
        $this->postType = $postType;

        $context['post_id'] = $postId;
        $context['post_type'] = $postType;

        parent::__construct($message, $context, $code, $previous);
    }

    public static function notFound(int $postId): self
    {
        return new self(
            sprintf("Post tidak ditemukan dengan ID: %d", $postId),
            $postId,
            null,
            [],
            self::NOT_FOUND
        );
    }

    public static function createFailed(string $title, string $postType, string $error = ''): self
    {
        return new self(
            sprintf("Gagal membuat post '%s' (tipe: %s). Error: %s", $title, $postType, $error),
            null,
            $postType,
            ['title' => $title, 'error' => $error],
            self::CREATE_FAILED
        );
    }

    public static function invalidPostType(string $postType, array $allowedTypes): self
    {
        return new self(
            sprintf(
                "Post type tidak valid: %s. Type yang diperbolehkan: %s",
                $postType,
                implode(', ', $allowedTypes)
            ),
            null,
            $postType,
            ['allowed_types' => $allowedTypes],
            self::INVALID_POST_TYPE
        );
    }

    public function getPostId(): ?int
    {
        return $this->postId;
    }

    public function getPostType(): ?string
    {
        return $this->postType;
    }
}
