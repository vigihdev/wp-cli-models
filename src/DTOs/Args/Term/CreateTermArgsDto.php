<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args\Term;

use Vigihdev\WpCliModels\Contracts\Args\Term\CreateTermArgsInterface;
use Vigihdev\WpCliModels\DTOs\Args\BaseArgsDto;

/**
 * Class CreateTermArgsDto
 *
 * DTO untuk menyimpan dan mengakses data pembuatan term di WordPress
 */
final class CreateTermArgsDto extends BaseArgsDto implements CreateTermArgsInterface
{
    /**
     * Membuat instance objek CreateTermArgsDto dengan parameter yang ditentukan
     *
     * @param string $taxonomy Taksonomi term
     * @param string $term Nama term
     * @param string|null $slug Slug term
     * @param string|null $description Deskripsi term
     * @param int|null $parent ID parent term
     */
    public function __construct(
        private readonly string $taxonomy,
        private readonly string $term,
        private readonly ?string $slug = null,
        private readonly ?string $description = null,
        private readonly ?int $parent = null,
    ) {}

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
     * Mendapatkan nama dari term
     *
     * @return string Nama term
     */
    public function getTerm(): string
    {
        return $this->term;
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
     * Mendapatkan deskripsi dari term
     *
     * @return string|null Deskripsi term
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Mendapatkan ID parent dari term
     *
     * @return int|null ID parent term
     */
    public function getParent(): ?int
    {
        return $this->parent;
    }

    /**
     * Membuat instance objek CreateTermArgsDto dari array data menggunakan named arguments
     *
     * @param array $data Array data yang berisi informasi pembuatan term
     * @return static Instance objek CreateTermArgsDto baru
     * @throws InvalidArgumentException Jika data yang diberikan tidak valid
     */
    public static function fromArray(array $data): static
    {
        // Validasi required fields
        if (empty($data['taxonomy'])) {
            throw new \InvalidArgumentException('Taxonomy is required');
        }

        if (empty($data['term'])) {
            throw new \InvalidArgumentException('Term name is required');
        }

        // Validasi tipe data untuk field numerik
        if (isset($data['parent']) && !is_numeric($data['parent'])) {
            throw new \InvalidArgumentException('Parent must be a number');
        }

        return new static(
            taxonomy: $data['taxonomy'],
            term: $data['term'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            parent: isset($data['parent']) ? (int) $data['parent'] : null
        );
    }

    /**
     * Mengkonversi objek ke array asosiatif yang sesuai dengan argumen WordPress
     *
     * @return array Argumen dalam bentuk array asosiatif
     */
    public function toArray(): array
    {
        $args = [
            'slug'        => $this->slug,
            'description' => $this->description,
            'parent'      => $this->parent,
        ];

        // Hapus nilai null agar tidak mempengaruhi pembuatan term
        $args = array_filter($args, function ($value) {
            return $value !== null;
        });

        return $args;
    }
}
