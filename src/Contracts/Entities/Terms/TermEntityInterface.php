<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Entities\Terms;

/**
 * Interface TermEntityInterface
 *
 * Interface untuk mendefinisikan struktur data term entity
 */
interface TermEntityInterface
{
    /**
     * Mendapatkan ID dari term
     *
     * @return int ID dari term
     */
    public function getTermId(): int;

    /**
     * Mendapatkan nama dari term
     *
     * @return string Nama dari term
     */
    public function getName(): string;

    /**
     * Mendapatkan slug dari term
     *
     * @return string Slug dari term
     */
    public function getSlug(): string;

    /**
     * Mendapatkan group dari term
     *
     * @return int Group dari term
     */
    public function getTermGroup(): int;

    /**
     * Mendapatkan ID dari term taxonomy
     *
     * @return int ID dari term taxonomy
     */
    public function getTermTaxonomyId(): int;

    /**
     * Mendapatkan nama taksonomi
     *
     * @return string Nama taksonomi
     */
    public function getTaxonomy(): string;

    /**
     * Mendapatkan deskripsi taksonomi
     *
     * @return string Deskripsi taksonomi
     */
    public function getDescription(): string;

    /**
     * Mendapatkan ID parent dari term ini
     *
     * @return int ID parent
     */
    public function getParent(): int;

    /**
     * Mendapatkan jumlah item dalam taksonomi ini
     *
     * @return int Jumlah item
     */
    public function getCount(): int;

    /**
     * Mendapatkan filter yang digunakan
     *
     * @return string Filter yang digunakan
     */
    public function getFilter(): string;
}