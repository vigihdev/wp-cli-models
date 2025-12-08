<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\TermFieldInterface;

/**
 * Class TermFieldDto
 *
 * DTO untuk menyimpan dan mengakses data field penting dari term di WP-CLI
 */
final class TermFieldDto extends BaseFieldDto implements TermFieldInterface
{
    /**
     * Membuat instance objek TermFieldDto dengan parameter yang ditentukan
     *
     * @param string      $name        Nama term
     * @param string|null $slug        Slug term
     * @param string      $taxonomy    Taksonomi term
     * @param string|null $description Deskripsi term
     */
    public function __construct(
        private readonly string $name,
        private readonly string $taxonomy,
        private readonly ?string $slug,
        private readonly ?string $description,
    ) {}

    /**
     * Mendapatkan nama dari term
     *
     * @return string Nama term
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Mendapatkan slug dari term
     *
     * @return string|null Slug term
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Mendapatkan taksonomi dari term
     *
     * @return string Taksonomi term
     */
    public function getTaxonomy(): string
    {
        return $this->taxonomy;
    }

    /**
     * Mendapatkan deskripsi dari term
     *
     * @return string|null Deskripsi term
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Mengkonversi objek TermFieldDto menjadi array
     *
     * @return array<string, mixed> Array asosiatif yang berisi data term
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'taxonomy' => $this->getTaxonomy(),
            'description' => $this->getDescription(),
        ], function ($value) {
            return $value !== null;
        });
    }

    /**
     * Membuat instance TermFieldDto dari array data
     *
     * @param array<string, mixed> $data Data untuk membuat objek TermFieldDto
     *
     * @return self Instance baru dari TermFieldDto
     */
    public static function fromArray(array $data): static
    {
        return new self(
            (string) ($data['name'] ?? ''),
            isset($data['slug']) ? (string) $data['slug'] : null,
            (string) ($data['taxonomy'] ?? ''),
            isset($data['description']) ? (string) $data['description'] : null,
        );
    }
}
