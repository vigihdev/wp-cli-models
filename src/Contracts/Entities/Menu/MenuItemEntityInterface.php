<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Entities\Menu;

/**
 * Interface MenuItemEntityInterface
 *
 * Interface untuk mendefinisikan struktur data menu item
 */
interface MenuItemEntityInterface
{
    /**
     * Mendapatkan ID dari menu item
     *
     * @return int ID menu item
     */
    public function getId(): int;

    /**
     * Mendapatkan post author dari menu item
     *
     * @return string Author menu item
     */
    public function getAuthor(): string;

    /**
     * Mendapatkan post date dari menu item
     *
     * @return string Tanggal pembuatan menu item
     */
    public function getDate(): string;

    /**
     * Mendapatkan post date GMT dari menu item
     *
     * @return string Tanggal pembuatan menu item dalam format GMT
     */
    public function getDateGmt(): string;

    /**
     * Mendapatkan post content dari menu item
     *
     * @return string Konten menu item
     */
    public function getContent(): string;

    /**
     * Mendapatkan post title dari menu item
     *
     * @return string Judul menu item
     */
    public function getPostTitle(): string;

    /**
     * Mendapatkan post excerpt dari menu item
     *
     * @return string Kutipan menu item
     */
    public function getExcerpt(): string;

    /**
     * Mendapatkan post status dari menu item
     *
     * @return string Status menu item
     */
    public function getStatus(): string;

    /**
     * Mendapatkan comment status dari menu item
     *
     * @return string Status komentar menu item
     */
    public function getCommentStatus(): string;

    /**
     * Mendapatkan ping status dari menu item
     *
     * @return string Status ping menu item
     */
    public function getPingStatus(): string;

    /**
     * Mendapatkan post password dari menu item
     *
     * @return string Password menu item
     */
    public function getPassword(): string;

    /**
     * Mendapatkan post name dari menu item
     *
     * @return string Nama/slug menu item
     */
    public function getName(): string;

    /**
     * Mendapatkan to ping dari menu item
     *
     * @return string To ping menu item
     */
    public function getToPing(): string;

    /**
     * Mendapatkan pinged dari menu item
     *
     * @return string Pinged menu item
     */
    public function getPinged(): string;

    /**
     * Mendapatkan post modified dari menu item
     *
     * @return string Tanggal modifikasi menu item
     */
    public function getModified(): string;

    /**
     * Mendapatkan post modified GMT dari menu item
     *
     * @return string Tanggal modifikasi menu item dalam format GMT
     */
    public function getModifiedGmt(): string;

    /**
     * Mendapatkan post content filtered dari menu item
     *
     * @return string Konten yang telah difilter
     */
    public function getContentFiltered(): string;

    /**
     * Mendapatkan post parent dari menu item
     *
     * @return int ID parent menu item
     */
    public function getParent(): int;

    /**
     * Mendapatkan GUID dari menu item
     *
     * @return string GUID menu item
     */
    public function getGuid(): string;

    /**
     * Mendapatkan menu order dari menu item
     *
     * @return int Urutan menu item
     */
    public function getMenuOrder(): int;

    /**
     * Mendapatkan post type dari menu item
     *
     * @return string Tipe post menu item
     */
    public function getPostType(): string;

    /**
     * Mendapatkan post mime type dari menu item
     *
     * @return string Mime type menu item
     */
    public function getMimeType(): string;

    /**
     * Mendapatkan comment count dari menu item
     *
     * @return string Jumlah komentar
     */
    public function getCommentCount(): string;

    /**
     * Mendapatkan filter dari menu item
     *
     * @return string Filter yang digunakan
     */
    public function getFilter(): string;

    /**
     * Mendapatkan database ID dari menu item
     *
     * @return int Database ID menu item
     */
    public function getDbId(): int;

    /**
     * Mendapatkan menu item parent dari menu item
     *
     * @return string ID parent menu item
     */
    public function getMenuItemParent(): string;

    /**
     * Mendapatkan object ID dari menu item
     *
     * @return string ID objek yang direferensikan
     */
    public function getObjectId(): string;

    /**
     * Mendapatkan object dari menu item
     *
     * @return string Jenis objek yang direferensikan
     */
    public function getObject(): string;

    /**
     * Mendapatkan type dari menu item
     *
     * @return string Tipe menu item
     */
    public function getType(): string;

    /**
     * Mendapatkan type label dari menu item
     *
     * @return string Label tipe menu item
     */
    public function getTypeLabel(): string;

    /**
     * Mendapatkan title dari menu item
     *
     * @return string Judul menu item
     */
    public function getTitle(): string;

    /**
     * Mendapatkan URL dari menu item
     *
     * @return string URL menu item
     */
    public function getUrl(): string;

    /**
     * Mendapatkan target dari menu item
     *
     * @return string Target link (_blank, _self, dll)
     */
    public function getTarget(): string;

    /**
     * Mendapatkan attr title dari menu item
     *
     * @return string Title atribut
     */
    public function getAttrTitle(): string;

    /**
     * Mendapatkan description dari menu item
     *
     * @return string Deskripsi menu item
     */
    public function getDescription(): string;

    /**
     * Mendapatkan classes dari menu item
     *
     * @return array Kelas CSS untuk menu item
     */
    public function getClasses(): array;

    /**
     * Mendapatkan XFN dari menu item
     *
     * @return string Nilai XFN (XHTML Friends Network)
     */
    public function getXfn(): string;
}
