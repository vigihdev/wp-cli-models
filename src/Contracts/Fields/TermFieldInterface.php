<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Fields;

/**
 * Interface TermFieldInterface
 *
 * Interface untuk mendefinisikan field-field penting dari term di WP-CLI
 */
interface TermFieldInterface
{

    /**
     * Mendapatkan nama dari term
     *
     * @return string Nama term
     */
    public function getName(): string;

    /**
     * Mendapatkan slug dari term
     *
     * @return string|null Slug term
     */
    public function getSlug(): ?string;

    /**
     * Mendapatkan taksonomi dari term
     *
     * @return string Taksonomi term
     */
    public function getTaxonomy(): string;

    /**
     * Mendapatkan deskripsi dari term
     *
     * @return string|null Deskripsi term
     */
    public function getDescription(): ?string;
}
