<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Entities\Terms;

/**
 * Interface TermTaxonomyEntityInterface
 *
 * Interface untuk mendefinisikan struktur data term taxonomy entity
 */
interface TermTaxonomyEntityInterface
{
    /**
     * Mendapatkan ID dari term taxonomy
     *
     * @return int ID dari term taxonomy
     */
    public function getTermTaxonomyId(): int;

    /**
     * Mendapatkan ID dari term
     *
     * @return int ID dari term
     */
    public function getTermId(): int;

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
}