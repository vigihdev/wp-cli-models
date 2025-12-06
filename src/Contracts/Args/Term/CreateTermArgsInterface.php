<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Args\Term;

/**
 * Interface CreateTermArgsInterface
 *
 * Interface untuk mendefinisikan struktur data pembuatan term di WordPress
 */
interface CreateTermArgsInterface
{
    /**
     * Mendapatkan taksonomi dari term
     *
     * @return string Taksonomi term
     */
    public function getTaxonomy(): string;

    /**
     * Mendapatkan nama dari term
     *
     * @return string Nama term
     */
    public function getTerm(): string;

    /**
     * Mendapatkan slug dari term
     *
     * @return string|null Slug term
     */
    public function getSlug(): ?string;

    /**
     * Mendapatkan deskripsi dari term
     *
     * @return string|null Deskripsi term
     */
    public function getDescription(): ?string;

    /**
     * Mendapatkan ID parent dari term
     *
     * @return int|null ID parent term
     */
    public function getParent(): ?int;
}
