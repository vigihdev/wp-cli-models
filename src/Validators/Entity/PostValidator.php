<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Entity;

use Vigihdev\WpCliModels\Exceptions\PostException;
use WP_Post;

final class PostValidator
{
    private ?WP_Post $post;
    private array $allowedPostTypes = [];

    public function __construct(
        private readonly int $id,
        array $allowedPostTypes = []
    ) {
        $this->post = get_post($id);
        $this->allowedPostTypes = $allowedPostTypes ?: get_post_types(['public' => true]);
    }

    /**
     * Validasi bahwa post harus exist
     */
    public function mustExist(): self
    {
        if (!$this->post) {
            throw PostException::notFound($this->id);
        }
        return $this;
    }

    /**
     * Validasi bahwa post type valid
     */
    public function mustHaveValidPostType(): self
    {
        $this->mustExist();

        if (!in_array($this->post->post_type, $this->allowedPostTypes)) {
            throw PostException::invalidPostType(
                $this->post->post_type,
                $this->allowedPostTypes
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post type tertentu
     */
    public function mustBePostType(string|array $postType): self
    {
        $this->mustExist();

        $expectedTypes = is_array($postType) ? $postType : [$postType];

        if (!in_array($this->post->post_type, $expectedTypes)) {
            throw new PostException(
                sprintf(
                    "Post ID %d bukan tipe %s (tipe: %s)",
                    $this->id,
                    implode(' atau ', $expectedTypes),
                    $this->post->post_type
                ),
                $this->id,
                $this->post->post_type,
                [
                    'expected_types' => $expectedTypes,
                    'actual_type' => $this->post->post_type
                ],
                PostException::INVALID_POST_TYPE
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post adalah page
     */
    public function mustBePage(): self
    {
        return $this->mustBePostType('page');
    }

    /**
     * Validasi bahwa post adalah post (article)
     */
    public function mustBePost(): self
    {
        return $this->mustBePostType('post');
    }

    /**
     * Validasi bahwa post adalah custom post type
     */
    public function mustBeCustomPostType(string $cpt): self
    {
        return $this->mustBePostType($cpt);
    }

    /**
     * Validasi bahwa post memiliki status tertentu
     */
    public function mustHaveStatus(string $status): self
    {
        $this->mustExist();

        if ($this->post->post_status !== $status) {
            throw new PostException(
                sprintf(
                    "Post ID %d tidak berstatus '%s' (status: %s)",
                    $this->id,
                    $status,
                    $this->post->post_status
                ),
                $this->id,
                $this->post->post_type,
                [
                    'expected_status' => $status,
                    'actual_status' => $this->post->post_status
                ],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post berstatus publish
     */
    public function mustBePublished(): self
    {
        return $this->mustHaveStatus('publish');
    }

    /**
     * Validasi bahwa post berstatus draft
     */
    public function mustBeDraft(): self
    {
        return $this->mustHaveStatus('draft');
    }

    /**
     * Validasi bahwa post memiliki parent tertentu
     */
    public function mustHaveParent(int $parentId): self
    {
        $this->mustExist();

        if ($this->post->post_parent != $parentId) {
            throw new PostException(
                sprintf(
                    "Post ID %d tidak memiliki parent ID %d (parent: %d)",
                    $this->id,
                    $parentId,
                    $this->post->post_parent
                ),
                $this->id,
                $this->post->post_type,
                [
                    'expected_parent' => $parentId,
                    'actual_parent' => $this->post->post_parent
                ],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki parent (bukan top-level)
     */
    public function mustHaveParentPost(): self
    {
        $this->mustExist();

        if ($this->post->post_parent == 0) {
            throw new PostException(
                sprintf("Post ID %d tidak memiliki parent (top-level post)", $this->id),
                $this->id,
                $this->post->post_type,
                [],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post adalah top-level (tidak punya parent)
     */
    public function mustBeTopLevel(): self
    {
        $this->mustExist();

        if ($this->post->post_parent != 0) {
            throw new PostException(
                sprintf(
                    "Post ID %d bukan top-level post (parent: %d)",
                    $this->id,
                    $this->post->post_parent
                ),
                $this->id,
                $this->post->post_type,
                ['parent_id' => $this->post->post_parent],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki title
     */
    public function mustHaveTitle(): self
    {
        $this->mustExist();

        if (empty($this->post->post_title)) {
            throw new PostException(
                sprintf("Post ID %d tidak memiliki title", $this->id),
                $this->id,
                $this->post->post_type,
                [],
                PostException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki content
     */
    public function mustHaveContent(): self
    {
        $this->mustExist();

        if (empty($this->post->post_content)) {
            throw new PostException(
                sprintf("Post ID %d tidak memiliki content", $this->id),
                $this->id,
                $this->post->post_type,
                [],
                PostException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki excerpt
     */
    public function mustHaveExcerpt(): self
    {
        $this->mustExist();

        if (empty($this->post->post_excerpt)) {
            throw new PostException(
                sprintf("Post ID %d tidak memiliki excerpt", $this->id),
                $this->id,
                $this->post->post_type,
                [],
                PostException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki featured image
     */
    public function mustHaveFeaturedImage(): self
    {
        $this->mustExist();

        if (!has_post_thumbnail($this->id)) {
            throw new PostException(
                sprintf("Post ID %d tidak memiliki featured image", $this->id),
                $this->id,
                $this->post->post_type,
                [],
                PostException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki terms dari taxonomy tertentu
     */
    public function mustHaveTerms(string $taxonomy, int $minCount = 1): self
    {
        $this->mustExist();

        $terms = wp_get_post_terms($this->id, $taxonomy);
        $termCount = is_array($terms) ? count($terms) : 0;

        if ($termCount < $minCount) {
            throw new PostException(
                sprintf(
                    "Post ID %d harus memiliki minimal %d terms dari taxonomy '%s' (saat ini: %d)",
                    $this->id,
                    $minCount,
                    $taxonomy,
                    $termCount
                ),
                $this->id,
                $this->post->post_type,
                [
                    'taxonomy' => $taxonomy,
                    'required_count' => $minCount,
                    'current_count' => $termCount
                ],
                PostException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki specific term
     */
    public function mustHaveTerm(int|string $term, string $taxonomy): self
    {
        $this->mustExist();

        $hasTerm = has_term($term, $taxonomy, $this->id);

        if (!$hasTerm) {
            $termName = is_numeric($term) ? "ID $term" : "'$term'";
            throw new PostException(
                sprintf(
                    "Post ID %d tidak memiliki term %s dari taxonomy '%s'",
                    $this->id,
                    $termName,
                    $taxonomy
                ),
                $this->id,
                $this->post->post_type,
                [
                    'taxonomy' => $taxonomy,
                    'term' => $term
                ],
                PostException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki meta key tertentu
     */
    public function mustHaveMeta(string $metaKey, $expectedValue = null): self
    {
        $this->mustExist();

        $metaValue = get_post_meta($this->id, $metaKey, true);

        if ($metaValue === '' || $metaValue === false || $metaValue === null) {
            throw new PostException(
                sprintf("Post ID %d tidak memiliki meta key '%s'", $this->id, $metaKey),
                $this->id,
                $this->post->post_type,
                ['meta_key' => $metaKey],
                PostException::CREATE_FAILED
            );
        }

        if ($expectedValue !== null && $metaValue != $expectedValue) {
            throw new PostException(
                sprintf(
                    "Post ID %d meta key '%s' tidak sama dengan nilai yang diharapkan. " .
                        "Diharapkan: %s, Ditemukan: %s",
                    $this->id,
                    $metaKey,
                    var_export($expectedValue, true),
                    var_export($metaValue, true)
                ),
                $this->id,
                $this->post->post_type,
                [
                    'meta_key' => $metaKey,
                    'expected_value' => $expectedValue,
                    'actual_value' => $metaValue
                ],
                PostException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki template tertentu
     */
    public function mustHaveTemplate(string $template): self
    {
        $this->mustExist();

        $currentTemplate = get_page_template_slug($this->id);

        if ($currentTemplate !== $template) {
            throw new PostException(
                sprintf(
                    "Post ID %d tidak menggunakan template '%s' (template: %s)",
                    $this->id,
                    $template,
                    $currentTemplate ?: 'default'
                ),
                $this->id,
                $this->post->post_type,
                [
                    'expected_template' => $template,
                    'actual_template' => $currentTemplate
                ],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post tidak trashed
     */
    public function mustNotBeTrashed(): self
    {
        $this->mustExist();

        if ($this->post->post_status === 'trash') {
            throw new PostException(
                sprintf("Post ID %d berada di trash", $this->id),
                $this->id,
                $this->post->post_type,
                [],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post adalah sticky (untuk posts saja)
     */
    public function mustBeSticky(): self
    {
        $this->mustBePost();

        if (!is_sticky($this->id)) {
            throw new PostException(
                sprintf("Post ID %d bukan sticky post", $this->id),
                $this->id,
                $this->post->post_type,
                [],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki comment status tertentu
     */
    public function mustHaveCommentStatus(string $status): self
    {
        $this->mustExist();

        if ($this->post->comment_status !== $status) {
            throw new PostException(
                sprintf(
                    "Post ID %d tidak memiliki comment status '%s' (status: %s)",
                    $this->id,
                    $status,
                    $this->post->comment_status
                ),
                $this->id,
                $this->post->post_type,
                [
                    'expected_status' => $status,
                    'actual_status' => $this->post->comment_status
                ],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post memiliki ping status tertentu
     */
    public function mustHavePingStatus(string $status): self
    {
        $this->mustExist();

        if ($this->post->ping_status !== $status) {
            throw new PostException(
                sprintf(
                    "Post ID %d tidak memiliki ping status '%s' (status: %s)",
                    $this->id,
                    $status,
                    $this->post->ping_status
                ),
                $this->id,
                $this->post->post_type,
                [
                    'expected_status' => $status,
                    'actual_status' => $this->post->ping_status
                ],
                PostException::UPDATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa post adalah revision dari post tertentu
     */
    public function mustBeRevisionOf(int $parentId): self
    {
        $this->mustExist();

        if ($this->post->post_type !== 'revision' || $this->post->post_parent != $parentId) {
            throw new PostException(
                sprintf(
                    "Post ID %d bukan revision dari post ID %d",
                    $this->id,
                    $parentId
                ),
                $this->id,
                $this->post->post_type,
                ['expected_parent' => $parentId],
                PostException::INVALID_POST_TYPE
            );
        }
        return $this;
    }

    /**
     * Get post object setelah validasi
     */
    public function getPost(): WP_Post
    {
        $this->mustExist();
        return $this->post;
    }

    /**
     * Get post ID setelah validasi
     */
    public function getId(): int
    {
        $this->mustExist();
        return $this->id;
    }

    /**
     * Get post title setelah validasi
     */
    public function getTitle(): string
    {
        $this->mustHaveTitle();
        return $this->post->post_title;
    }

    /**
     * Get post content setelah validasi
     */
    public function getContent(): string
    {
        $this->mustHaveContent();
        return $this->post->post_content;
    }

    /**
     * Static factory method
     */
    public static function validate(int $id, array $allowedPostTypes = []): self
    {
        return new self($id, $allowedPostTypes);
    }

    /**
     * Helper: Check if post exists
     */
    public static function exists(int $id, string $postType = ''): bool
    {
        try {
            $validator = new self($id);
            $validator->mustExist();

            if ($postType && $validator->post->post_type !== $postType) {
                return false;
            }

            return true;
        } catch (PostException $e) {
            return false;
        }
    }

    /**
     * Helper: Find post by title
     */
    public static function findByTitle(string $title, string $postType = 'post'): ?int
    {
        $post = get_page_by_title($title, OBJECT, $postType);
        return $post ? $post->ID : null;
    }

    /**
     * Helper: Get posts by meta value
     */
    public static function findByMeta(string $metaKey, $metaValue, string $postType = ''): array
    {
        $args = [
            'meta_key' => $metaKey,
            'meta_value' => $metaValue,
            'post_type' => $postType ?: 'any',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ];

        $query = new \WP_Query($args);
        return $query->posts ?: [];
    }
}
