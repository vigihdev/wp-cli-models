<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Fields;

/**
 * Interface PostTypeFieldInterface
 *
 * Interface untuk mendefinisikan field-field penting dari post di WP-CLI
 */
interface PostTypeFieldInterface
{
    /**
     * Mendapatkan judul dari post
     *
     * @return string Judul post
     */
    public function getTitle(): string;

    /**
     * Mendapatkan konten dari post
     *
     * @return string Konten post
     */
    public function getContent(): string;

    /**
     * Mendapatkan status dari post
     *
     * @return string Status post
     */
    public function getStatus(): string;

    /**
     * Mendapatkan tipe post
     *
     * @return string Tipe post
     */
    public function getType(): string;

    /**
     * Mendapatkan author ID dari post
     *
     * @return int ID author
     */
    public function getAuthor(): int;

    /**
     * @return array<string,int[]>
     */
    public function getTaxInput(): array;
}
