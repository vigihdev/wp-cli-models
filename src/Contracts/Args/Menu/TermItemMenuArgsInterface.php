<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Args\Menu;

/**
 * Interface TermItemMenuArgsInterface
 *
 * Interface untuk mendefinisikan struktur data perintah penambahan term item menu di WP-CLI
 */
interface TermItemMenuArgsInterface
{
    /**
     * Mendapatkan menu identifier dari perintah
     *
     * @return string Nama, slug, atau term ID untuk menu
     */
    public function getMenu(): string;

    /**
     * Mendapatkan taxonomy untuk term
     *
     * @return string Nama taxonomy dari term yang akan ditambahkan ke menu
     */
    public function getTaxonomy(): string;

    /**
     * Mendapatkan term ID untuk menu item
     *
     * @return int ID dari term yang akan ditambahkan ke menu
     */
    public function getTermId(): int;

    /**
     * Mendapatkan title dari menu item
     *
     * @return string|null Title untuk link jika dispesifikasikan
     */
    public function getTitle(): ?string;

    /**
     * Mendapatkan link URL dari menu item
     *
     * @return string|null Target URL untuk link jika dispesifikasikan
     */
    public function getLink(): ?string;

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
}
