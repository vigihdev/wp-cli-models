<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Args\Menu;

/**
 * Interface CustomItemMenuArgsInterface
 *
 * Interface untuk mendefinisikan struktur data perintah penambahan menu item custom di WP-CLI
 */
interface CustomItemMenuArgsInterface
{
    /**
     * Mendapatkan menu identifier dari perintah
     *
     * @return string Nama, slug, atau term ID untuk menu
     */
    public function getMenu(): string;

    /**
     * Mendapatkan title dari menu item
     *
     * @return string Title untuk link
     */
    public function getTitle(): string;

    /**
     * Mendapatkan link URL dari menu item
     *
     * @return string Target URL untuk link
     */
    public function getLink(): string;

    /**
     * Mendapatkan description dari menu item
     *
     * @return string|null Description untuk menu item jika diset
     */
    public function getDescription(): ?string;

    /**
     * Mendapatkan attribute title dari menu item
     *
     * @return string|null Attribute title untuk menu item jika diset
     */
    public function getAttrTitle(): ?string;

    /**
     * Mendapatkan target dari link menu item
     *
     * @return string|null Target link untuk menu item jika diset
     */
    public function getTarget(): ?string;

    /**
     * Mendapatkan classes dari link menu item
     *
     * @return string|null Classes untuk link menu item jika diset
     */
    public function getClasses(): ?string;

    /**
     * Mendapatkan position dari menu item
     *
     * @return int|null Posisi menu item jika dispesifikasikan
     */
    public function getPosition(): ?int;

    /**
     * Mendapatkan parent ID dari menu item
     *
     * @return int|null ID parent menu item jika merupakan child
     */
    public function getParentId(): ?int;

    /**
     * Mendapatkan status porcelain output
     *
     * @return bool True jika hanya menampilkan ID baru menu item
     */
    public function getPorcelain(): bool;
}
