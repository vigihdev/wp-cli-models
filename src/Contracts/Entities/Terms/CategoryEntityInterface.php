<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Entities\Terms;

/**
 * Interface CategoryEntityInterface
 *
 * Interface untuk mendefinisikan struktur data category entity
 */
interface CategoryEntityInterface
{

    /**
     * Mendapatkan ID kategori
     * 
     * @return int ID kategori
     */
    public function getTermId(): int;

    /**
     * Mendapatkan nama kategori
     *
     * @return string Nama kategori
     */
    public function getName(): string;

    /**
     * Mendapatkan slug kategori
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * Mendapatkan group kategori
     *
     * @return int Group kategori
     */
    public function getTermGroup(): int;

    /**
     * Mendapatkan ID dari term taxonomy
     *
     * @return int ID dari term taxonomy
     */
    public function getTermTaxonomyId(): int;

    /**
     * Mendapatkan taxonomy kategori
     *
     * @return string Taxonomy kategori
     */
    public function getTaxonomy(): string;
    /**
     * Mendapatkan deskripsi kategori
     *
     * @return string Deskripsi kategori
     */
    public function getDescription(): string;

    /**
     * Mendapatkan parent kategori
     *
     * @return int ID dari parent kategori
     */
    public function getParent(): int;

    /**
     * Mendapatkan jumlah post yang terkait dengan kategori
     *
     * @return int Jumlah post yang terkait dengan kategori
     */
    public function getCount(): int;

    /**
     * Mendapatkan filter kategori
     *
     * @return string Filter kategori
     */
    public function getFilter(): string;

    /**
     * Mendapatkan ID kategori
     *
     * @return int ID kategori
     */
    public function getCatId(): int;

    /**
     * Mendapatkan jumlah post yang terkait dengan kategori
     *
     * @return int Jumlah post yang terkait dengan kategori 
     */
    public function getCategoryCount(): int;

    /**
     * Mendapatkan deskripsi kategori
     *
     * @return string Deskripsi kategori
     */
    public function getCategoryDescription(): string;

    /**
     * Mendapatkan nama kategori
     *
     * @return string Nama kategori
     */
    public function getCatName(): string;

    /**
     * Mendapatkan slug kategori
     *
     * @return string Slug kategori
     */
    public function getCategoryNicename(): string;

    /**
     * Mendapatkan parent kategori
     *
     * @return int ID dari parent kategori
     */
    public function getCategoryParent(): int;
}
