<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Entities;

/**
 * Interface PostInterface
 *
 * Interface untuk mendefinisikan struktur data WordPress post
 */
interface PostInterface
{
    /**
     * Mendapatkan ID dari post
     *
     * @return int ID post
     */
    public function getId(): int;

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
     * Mendapatkan excerpt dari post
     *
     * @return string Excerpt post
     */
    public function getExcerpt(): string;

    /**
     * Mendapatkan status dari post
     *
     * @return string Status post (publish, draft, etc.)
     */
    public function getStatus(): string;

    /**
     * Mendapatkan slug dari post
     *
     * @return string Slug post
     */
    public function getSlug(): string;

    /**
     * Mendapatkan tanggal pembuatan post
     *
     * @return string Tanggal pembuatan post
     */
    public function getDate(): string;

    /**
     * Mendapatkan tanggal modifikasi post
     *
     * @return string Tanggal modifikasi post
     */
    public function getModifiedDate(): string;

    /**
     * Mendapatkan author ID dari post
     *
     * @return int ID author
     */
    public function getAuthorId(): int;

    /**
     * Mendapatkan tipe post
     *
     * @return string Tipe post
     */
    public function getPostType(): string;

    /**
     * Mendapatkan parent post ID
     *
     * @return int Parent post ID
     */
    public function getParentId(): int;

    /**
     * Mendapatkan comment status dari post
     *
     * @return string Comment status
     */
    public function getCommentStatus(): string;

    /**
     * Mendapatkan ping status dari post
     *
     * @return string Ping status
     */
    public function getPingStatus(): string;

    /**
     * Mendapatkan menu order dari post
     *
     * @return int Menu order
     */
    public function getMenuOrder(): int;

    /**
     * Mendapatkan meta data dari post
     *
     * @return array Meta data post
     */
    public function getMeta(): array;
}
