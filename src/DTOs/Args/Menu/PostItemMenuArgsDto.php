<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Args\Menu\PostItemMenuArgsInterface;

/**
 * Class PostItemMenuArgsDto
 *
 * DTO untuk menyimpan dan mengakses data perintah penambahan post item menu di WP-CLI
 */
final class PostItemMenuArgsDto implements PostItemMenuArgsInterface
{
    /**
     * Membuat instance objek PostItemMenuArgsDto dengan parameter yang ditentukan
     *
     * @param string $menu Nama, slug, atau term ID untuk menu
     * @param int $postId ID dari post yang akan ditambahkan ke menu
     * @param string|null $title Title untuk link jika dispesifikasikan
     * @param string|null $link Target URL untuk link jika dispesifikasikan
     * @param string|null $description Description untuk menu item jika diset
     * @param string|null $attrTitle Attribute title untuk menu item jika diset
     * @param string|null $target Target link untuk menu item jika diset
     * @param string|null $classes Classes untuk link menu item jika diset
     * @param int|null $position Posisi menu item jika dispesifikasikan
     * @param int|null $parentId ID parent menu item jika merupakan child
     * @param bool $porcelain True jika hanya menampilkan ID baru menu item
     */
    public function __construct(
        private readonly string $menu,
        private readonly int $postId,
        private readonly ?string $title = null,
        private readonly ?string $link = null,
        private readonly ?string $description = null,
        private readonly ?string $attrTitle = null,
        private readonly ?string $target = null,
        private readonly ?string $classes = null,
        private readonly ?int $position = null,
        private readonly ?int $parentId = null,
        private readonly bool $porcelain = false
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
     * Mendapatkan post ID untuk menu item
     *
     * @return int ID dari post yang akan ditambahkan ke menu
     */
    public function getPostId(): int
    {
        return $this->postId;
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

    /**
     * Mendapatkan status porcelain output
     *
     * @return bool True jika hanya menampilkan ID baru menu item
     */
    public function getPorcelain(): bool
    {
        return $this->porcelain;
    }
}
