<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTO\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Args\Menu\TermItemMenuArgsInterface;

/**
 * Class TermItemMenuArgsDto
 *
 * DTO untuk menyimpan dan mengakses data perintah penambahan term item menu di WP-CLI
 */
final class TermItemMenuArgsDto implements TermItemMenuArgsInterface
{
    /**
     * Membuat instance objek TermItemMenuArgsDto dengan parameter yang ditentukan
     *
     * @param string $menu Nama, slug, atau term ID untuk menu
     * @param string $taxonomy Nama taxonomy dari term yang akan ditambahkan ke menu
     * @param int $termId ID dari term yang akan ditambahkan ke menu
     * @param string|null $title Title untuk link jika dispesifikasikan
     * @param string|null $link Target URL untuk link jika dispesifikasikan
     * @param string|null $description Description untuk menu item jika diset
     * @param string|null $attrTitle Attribute title untuk menu item jika diset
     * @param string|null $target Target link untuk menu item jika diset
     * @param string|null $classes Classes untuk link menu item jika diset
     * @param int|null $position Posisi menu item jika dispesifikasikan
     * @param int|null $parentId ID parent menu item jika merupakan child
     */
    public function __construct(
        private readonly string $menu,
        private readonly string $taxonomy,
        private readonly int $termId,
        private readonly ?string $title = null,
        private readonly ?string $link = null,
        private readonly ?string $description = null,
        private readonly ?string $attrTitle = null,
        private readonly ?string $target = null,
        private readonly ?string $classes = null,
        private readonly ?int $position = null,
        private readonly ?int $parentId = null
    ) {}

    /**
     * Mendapatkan menu identifier dari perintah
     *
     * @return string Nama, slug, atau term ID untuk menu
     */
    public function getMenu(): string
    {
        return $this->menu;
    }

    /**
     * Mendapatkan taxonomy untuk term
     *
     * @return string Nama taxonomy dari term yang akan ditambahkan ke menu
     */
    public function getTaxonomy(): string
    {
        return $this->taxonomy;
    }

    /**
     * Mendapatkan term ID untuk menu item
     *
     * @return int ID dari term yang akan ditambahkan ke menu
     */
    public function getTermId(): int
    {
        return $this->termId;
    }

    /**
     * Mendapatkan title dari menu item
     *
     * @return string|null Title untuk link jika dispesifikasikan
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Mendapatkan link URL dari menu item
     *
     * @return string|null Target URL untuk link jika dispesifikasikan
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Mendapatkan description dari menu item
     *
     * @return string|null Description untuk menu item jika diset
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Mendapatkan attribute title dari menu item
     *
     * @return string|null Attribute title untuk menu item jika diset
     */
    public function getAttrTitle(): ?string
    {
        return $this->attrTitle;
    }

    /**
     * Mendapatkan target dari link menu item
     *
     * @return string|null Target link untuk menu item jika diset
     */
    public function getTarget(): ?string
    {
        return $this->target;
    }

    /**
     * Mendapatkan classes dari link menu item
     *
     * @return string|null Classes untuk link menu item jika diset
     */
    public function getClasses(): ?string
    {
        return $this->classes;
    }

    /**
     * Mendapatkan position dari menu item
     *
     * @return int|null Posisi menu item jika dispesifikasikan
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * Mendapatkan parent ID dari menu item
     *
     * @return int|null ID parent menu item jika merupakan child
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }
}
