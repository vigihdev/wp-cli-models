<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args\Term;

use Vigihdev\WpCliModels\Contracts\Args\Term\CreateTermArgsInterface;

/**
 * Class CreateTermArgsDto
 *
 * DTO untuk menyimpan dan mengakses data pembuatan term di WordPress
 */
final class CreateTermArgsDto implements CreateTermArgsInterface
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
}
