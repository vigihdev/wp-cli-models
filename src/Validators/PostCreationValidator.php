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

    public function mustNotEmptyTitle(string $title): self
    {
        if (empty(trim($title))) {
            throw PostCreationException::emptyPostTitle();
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

    private function mustBeValidPostType(string $postType): self
    {
        return $this;
    }

    private function mustBeValidCategory(array|int|string $category): self
    {

        if (is_array($category)) {
            $category = array_map(fn($cat) => (int) $cat, $category);
        }

        $category = is_string($category) ? (int) $category : $category;
        $categorys = is_array($category) ? $category : [$category];

        foreach ($categorys as $cat) {
            if (!get_term($cat, 'category')) {
                throw PostCreationException::invalidCategory($cat);
            }
        }

        return $this;
    }

    public function mustBeCreatable(): self
    {
        // Validasi dasar untuk data post
        if (!empty($this->postData)) {
            $post_type = isset($this->postData['post_type']) ? $this->postData['post_type'] : 'post';
            $post_title = isset($this->postData['post_title']) ? $this->postData['post_title'] : null;
            $post_name = isset($this->postData['post_name']) ? $this->postData['post_name'] : null;
            $post_status = isset($this->postData['post_status']) ? $this->postData['post_status'] : null;
            $post_author = isset($this->postData['post_author']) ? $this->postData['post_author'] : null;

            if ($post_author && !get_user_by('ID', $post_author)) {
                throw PostCreationException::invalidAuthor((int) $post_author);
            }

            if ($post_title && $post_type) {
                $this->mustHaveUniqueTitle($post_title, $post_type);
            }

            if ($post_status) {
                $this->mustHaveValidStatus($post_status);
            }
            if ($post_name && $post_type) {
                $this->mustHaveUniqueName($post_name, $post_type);
            }
        }

        return $this;
    }
}
