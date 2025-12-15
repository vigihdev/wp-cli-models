<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Entities\PostEntity;
use Vigihdev\WpCliModels\Exceptions\PostCreationException;
use WP_Post;

final class PostCreationValidator
{
    private ?WP_Post $post = null;
    private array $postData = [];

    public function __construct(
        private int|string|array $data,
    ) {
        if (is_numeric($data)) {
            $this->post = get_post((int) $data);
        } elseif (is_string($data)) {
            $this->postData = json_decode($data, true) ?: [];
        } elseif (is_array($data)) {
            $this->postData = $data;
        }
    }

    public static function validate(int|string|array $data): self
    {
        return new self($data);
    }

    public function mustBeValidAuthor(int $authorId): self
    {
        if (!get_user_by('ID', $authorId)) {
            throw PostCreationException::invalidAuthor($authorId);
        }
        return $this;
    }

    public function mustHaveUniqueTitle(string $title): self
    {

        if (PostEntity::existsByTitle($title)) {
            $post = PostEntity::findByTitle($title);
            throw PostCreationException::duplicatePostTitle($title, $post->post_type);
        }
        return $this;
    }

    public function mustHaveUniqueName(string $titleOrSlug): self
    {

        if (PostEntity::existsByName($titleOrSlug)) {
            $post = PostEntity::findByName($titleOrSlug);
            throw PostCreationException::duplicatePostTitle($titleOrSlug, $post->post_type);
        }

        return $this;
    }

    public function mustHaveValidStatus(string $status): self
    {
        $validStatuses = ['publish', 'draft', 'pending', 'private', 'future', 'trash'];

        if (!in_array($status, $validStatuses)) {
            throw PostCreationException::invalidStatus($status);
        }
        return $this;
    }

    public function mustBeCreatable(): self
    {
        // Validasi dasar untuk data post
        if (!empty($this->postData)) {
            if (isset($this->postData['post_author']) && !get_user_by('ID', $this->postData['post_author'])) {
                throw PostCreationException::invalidAuthor((int) $this->postData['post_author']);
            }

            if (isset($this->postData['post_title']) && isset($this->postData['post_type'])) {
                $this->mustHaveUniqueTitle($this->postData['post_title'], $this->postData['post_type'] ?? 'post');
            }

            if (isset($this->postData['post_status'])) {
                $this->mustHaveValidStatus($this->postData['post_status']);
            }
        }

        return $this;
    }
}
