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
    public function termId(): int;

    /**
     * Mendapatkan nama kategori
     *
     * @return string Nama kategori
     */
    public function name(): string;

    /**
     * Mendapatkan slug kategori
     *
     * @return string
     */
    public function slug(): string;

    /**
     * Mendapatkan group kategori
     *
     * @return int Group kategori
     */
    public function termGroup(): int;

    /**
     * Mendapatkan ID dari term taxonomy
     *
     * @return int ID dari term taxonomy
     */
    public function termTaxonomyId(): int;

    /**
     * Mendapatkan taxonomy kategori
     *
     * @return string Taxonomy kategori
     */
    public function taxonomy(): string;
    /**
     * Mendapatkan deskripsi kategori
     *
     * @return string Deskripsi kategori
     */
    public function description(): string;

    /**
     * Mendapatkan parent kategori
     *
     * @return int ID dari parent kategori
     */
    public function parent(): int;

    /**
     * Mendapatkan jumlah post yang terkait dengan kategori
     *
     * @return int Jumlah post yang terkait dengan kategori
     */
    public function count(): int;

    /**
     * Mendapatkan filter kategori
     *
     * @return string Filter kategori
     */
    public function filter(): string;

    /**
     * Mendapatkan ID kategori
     *
     * @return int ID kategori
     */
    public function catId(): int;

    /**
     * Mendapatkan jumlah post yang terkait dengan kategori
     *
     * @return int Jumlah post yang terkait dengan kategori 
     */
    public function categoryCount(): int;

    /**
     * Mendapatkan deskripsi kategori
     *
     * @return string Deskripsi kategori
     */
    public function categoryDescription(): string;

    /**
     * Mendapatkan nama kategori
     *
     * @return string Nama kategori
     */
    public function catName(): string;

    /**
     * Mendapatkan slug kategori
     *
     * @return string Slug kategori
     */
    public function categoryNicename(): string;

    /**
     * Mendapatkan parent kategori
     *
     * @return int ID dari parent kategori
     */
    public function categoryParent(): int;
}
