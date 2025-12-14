<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Exceptions\PostException;
use WP_Post;

final class PostValidator
{
    private ?WP_Post $post = null;

    public function __construct(
        private int $id,
    ) {
        $this->post = get_post($id);
    }

    public static function validate(int $id): self
    {
        return new self($id);
    }

    public function mustBeExist(): self
    {
        if (!$this->post) {
            throw PostException::notFound($this->id);
        }
        return $this;
    }

    public function mustBeType(string $type): self
    {
        if ($this->post->post_type !== $type) {
            throw PostException::invalidPostType($this->post->post_type, [$type]);
        }
        return $this;
    }

    public function mustBeStatus(string $status): self
    {
        if ($this->post->post_status !== $status) {
            throw PostException::invalidStatus($this->post->post_status, [$status]);
        }
        return $this;
    }

    public function mustHaveTitle(): self
    {
        if (empty($this->post->post_title)) {
            throw PostException::createFailed($this->post->post_title, $this->post->post_type, 'Title tidak boleh kosong');
        }
        return $this;
    }

    public function mustHaveContent(): self
    {
        if (empty($this->post->post_content)) {
            throw PostException::createFailed($this->post->post_title, $this->post->post_type, 'Content tidak boleh kosong');
        }
        return $this;
    }
}
