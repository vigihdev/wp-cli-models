<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args;

use DateTime;
use DateTimeInterface;
use Vigihdev\WpCliModels\Contracts\Args\PostArgsInterface;
use Vigihdev\WpCliModels\Enums\PostStatus;
use Vigihdev\WpCliModels\Enums\PostType;

/**
 * Class PostArgsDto
 *
 * DTO untuk menyimpan dan mengakses data argumen post
 */
final class PostArgsDto implements PostArgsInterface
{
    /**
     * Membuat instance objek PostArgsDto dengan parameter yang ditentukan
     *
     * @param string $title Judul post
     * @param ?int $id ID post
     * @param ?string $content Konten post
     * @param ?string $status Status post (publish, draft, private, etc.)
     * @param ?string $slug Slug post
     * @param ?string $excerpt Excerpt post
     * @param ?int $author ID author
     * @param array $categories Array kategori
     * @param array $tags Array tag
     * @param ?int $featuredImage ID featured image
     * @param ?string $date Tanggal publikasi
     * @param ?string $type Tipe post
     */
    public function __construct(
        private readonly string $title,
        private readonly ?int $id = null,
        private readonly ?string $content = null,
        private readonly ?string $status = null,
        private readonly ?string $slug = null,
        private readonly ?string $excerpt = null,
        private readonly ?int $author = null,
        private readonly array $categories = [],
        private readonly array $tags = [],
        private readonly ?int $featuredImage = null,
        private readonly ?string $date = null,
        private readonly ?string $type = null
    ) {}

    /**
     * Mendapatkan ID post
     *
     * @return int|null ID post
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Mendapatkan judul post
     *
     * @return string Judul post
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Mendapatkan konten post
     *
     * @return string|null Konten post
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Mendapatkan status post
     *
     * @return PostStatus|null Status post (publish, draft, private, etc.)
     */
    public function getStatus(): ?PostStatus
    {
        return $this->status;
    }

    /**
     * Mendapatkan slug post
     *
     * @return string|null Slug post
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Mendapatkan excerpt post
     *
     * @return string|null Excerpt post
     */
    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    /**
     * Mendapatkan author ID
     *
     * @return int|null ID author
     */
    public function getAuthor(): ?int
    {
        return $this->author;
    }

    /**
     * Mendapatkan kategori-kategori post
     *
     * @return array Array kategori
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Mendapatkan tag-tag post
     *
     * @return array Array tag
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Mendapatkan featured image ID
     *
     * @return int|null ID featured image
     */
    public function getFeaturedImage(): ?int
    {
        return $this->featuredImage;
    }

    /**
     * Mendapatkan tanggal publikasi post
     *
     * @return DateTimeInterface|null Tanggal publikasi
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Mendapatkan tipe post
     *
     * @return PostType|null Tipe post
     */
    public function getType(): ?PostType
    {
        return $this->type;
    }

    /**
     * Mendapatkan status post sebagai string (untuk WordPress)
     */
    public function getStatusValue(): ?string
    {
        return $this->status?->value;
    }

    /**
     * Mendapatkan tipe post sebagai string (untuk WordPress)
     */
    public function getTypeValue(): ?string
    {
        return $this->type?->value;
    }

    /**
     * Mendapatkan tanggal dalam format WordPress
     */
    public function getDateString(): ?string
    {
        return $this->date?->format('Y-m-d H:i:s');
    }

    public static function fromArray(array $data): static
    {
        // Parse enums dari string
        $type = isset($data['type']) && is_string($data['type'])
            ? PostType::tryFrom($data['type'])
            : null;

        $status = isset($data['status']) && is_string($data['status'])
            ? PostStatus::tryFrom($data['status'])
            : null;

        // Parse tanggal
        $date = null;
        if (isset($data['date']) && is_string($data['date'])) {
            try {
                $date = new \DateTime($data['date']);
            } catch (\Exception $e) {
                // Tangani error parsing tanggal
            }
        }

        return new static(
            title: $data['title'] ?? '',
            type: $type,
            status: $status,
            content: $data['content'] ?? null,
            slug: $data['slug'] ?? null,
            excerpt: $data['excerpt'] ?? null,
            author: $data['author'] ?? null,
            categories: $data['categories'] ?? [],
            tags: $data['tags'] ?? [],
            featuredImage: $data['featured_image'] ?? $data['featuredImage'] ?? null,
            date: $date,
            id: $data['id'] ?? null
        );
    }

    public function toArray(): array
    {
        $timeZone = new \DateTimeZone('UTC');
        return [
            'ID' => $this->id,
            'post_title' => $this->title,
            'post_type' => $this->getTypeValue(),
            'post_status' => $this->getStatusValue(),
            'post_content' => $this->content,
            'post_name' => $this->slug,
            'post_excerpt' => $this->excerpt,
            'post_author' => $this->author,
            'post_category' => $this->categories,
            'tags_input' => $this->tags,
            'post_thumbnail' => $this->featuredImage,
            'post_date' => $this->getDateString(),
            'post_date_gmt' => (new DateTime($this->getDateString(), $timeZone))->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Validasi data DTO
     */
    public function validate(): void
    {
        if (empty(trim($this->title))) {
            throw new \InvalidArgumentException('Post title cannot be empty');
        }

        if ($this->author && $this->author < 1) {
            throw new \InvalidArgumentException('Author ID must be positive');
        }
    }
}
