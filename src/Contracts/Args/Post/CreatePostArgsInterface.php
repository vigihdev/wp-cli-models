<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Args\Post;

/**
 * Interface CreatePostArgsInterface
 *
 * Interface untuk mendefinisikan struktur data pembuatan post di WordPress
 */
interface CreatePostArgsInterface
{
    /**
     * Mendapatkan ID author dari post
     *
     * @return int|null ID user yang membuat post
     */
    public function getAuthor(): ?int;

    /**
     * Mendapatkan tanggal pembuatan post
     *
     * @return string|null Tanggal pembuatan post
     */
    public function getDate(): ?string;

    /**
     * Mendapatkan tanggal pembuatan post dalam zona waktu GMT
     *
     * @return string|null Tanggal pembuatan post dalam GMT
     */
    public function getDateGmt(): ?string;

    /**
     * Mendapatkan konten dari post
     *
     * @return string Konten post
     */
    public function getContent(): string;

    /**
     * Mendapatkan konten post yang telah difilter
     *
     * @return string|null Konten post yang telah difilter
     */
    public function getContentFiltered(): ?string;

    /**
     * Mendapatkan judul dari post
     *
     * @return string Judul post
     */
    public function getTitle(): string;

    /**
     * Mendapatkan kutipan dari post
     *
     * @return string|null Kutipan post
     */
    public function getExcerpt(): ?string;

    /**
     * Mendapatkan status dari post
     *
     * @return string|null Status post
     */
    public function getStatus(): ?string;

    /**
     * Mendapatkan tipe dari post
     *
     * @return string|null Tipe post
     */
    public function getType(): ?string;

    /**
     * Mendapatkan status komentar untuk post
     *
     * @return string|null Status komentar post
     */
    public function getCommentStatus(): ?string;

    /**
     * Mendapatkan status ping untuk post
     *
     * @return string|null Status ping post
     */
    public function getPingStatus(): ?string;

    /**
     * Mendapatkan password dari post
     *
     * @return string|null Password post
     */
    public function getPassword(): ?string;

    /**
     * Mendapatkan slug nama post
     *
     * @return string|null Nama post (slug)
     */
    public function getName(): ?string;

    /**
     * Mendapatkan ID post sumber untuk duplikasi
     *
     * @return int|null ID post sumber
     */
    public function getFromPost(): ?int;

    /**
     * Mendapatkan daftar URL yang akan diping
     *
     * @return string|null Daftar URL untuk diping
     */
    public function getToPing(): ?string;

    /**
     * Mendapatkan daftar URL yang sudah diping
     *
     * @return string|null Daftar URL yang sudah diping
     */
    public function getPinged(): ?string;

    /**
     * Mendapatkan tanggal modifikasi post
     *
     * @return string|null Tanggal modifikasi post
     */
    public function getModified(): ?string;

    /**
     * Mendapatkan tanggal modifikasi post dalam zona waktu GMT
     *
     * @return string|null Tanggal modifikasi post dalam GMT
     */
    public function getModifiedGmt(): ?string;

    /**
     * Mendapatkan ID parent dari post
     *
     * @return int|null ID post parent
     */
    public function getParent(): ?int;

    /**
     * Mendapatkan urutan menu dari post
     *
     * @return int|null Urutan menu post
     */
    public function getMenuOrder(): ?int;

    /**
     * Mendapatkan tipe MIME dari post
     *
     * @return string|null Tipe MIME post
     */
    public function getMimeType(): ?string;

    /**
     * Mendapatkan GUID dari post
     *
     * @return string|null GUID post
     */
    public function getGuid(): ?string;

    /**
     * Mendapatkan kategori post
     *
     * @return array|null Array kategori post
     */
    public function getCategory(): ?array;

    /**
     * Mendapatkan tag input untuk post
     *
     * @return array|null Array tag post
     */
    public function getTagsInput(): ?array;

    /**
     * Mendapatkan input taksonomi untuk post
     *
     * @return array|null Array input taksonomi
     */
    public function getTaxInput(): ?array;

    /**
     * Mendapatkan input meta untuk post
     *
     * @return array|null Array input meta
     */
    public function getMetaInput(): ?array;
}
