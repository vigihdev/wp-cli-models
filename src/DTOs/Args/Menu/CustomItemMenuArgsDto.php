<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Args\Menu\CustomItemMenuArgsInterface;
use Vigihdev\WpCliModels\DTOs\Args\BaseArgsDto;

/**
 * Class CustomItemMenuArgsDto
 *
 * DTO untuk menyimpan dan mengakses data perintah penambahan menu item custom di WP-CLI
 */
final class CustomItemMenuArgsDto extends BaseArgsDto implements CustomItemMenuArgsInterface
{
    /**
     * Membuat instance objek CustomItemMenuArgsDto dengan parameter yang ditentukan
     *
     * @param string $menu Nama, slug, atau term ID untuk menu
     * @param string $title Title untuk link
     * @param string $link Target URL untuk link
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
        private readonly string $title,
        private readonly string $link,
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
     * Mendapatkan title dari menu item
     *
     * @return string Title untuk link
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Mendapatkan link URL dari menu item
     *
     * @return string Target URL untuk link
     */
    public function getLink(): string
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
    /**
     * Membuat instance objek CustomItemMenuArgsDto dari array data menggunakan named arguments
     *
     * @param array $data Array data yang berisi informasi menu item
     * @return static Instance objek CustomItemMenuArgsDto baru
     * @throws InvalidArgumentException Jika data yang diberikan tidak valid
     */
    public static function fromArray(array $data): static
    {
        // Validasi required fields
        if (!isset($data['menu'])) {
            throw new \InvalidArgumentException('Menu identifier is required');
        }

        if (!isset($data['title'])) {
            throw new \InvalidArgumentException('Title is required');
        }

        if (!isset($data['link'])) {
            throw new \InvalidArgumentException('Link URL is required');
        }

        // Validasi tipe data untuk field numerik
        if (isset($data['position']) && !is_numeric($data['position'])) {
            throw new \InvalidArgumentException('Position must be a number');
        }

        if (isset($data['parent_id']) && !is_numeric($data['parent_id'])) {
            throw new \InvalidArgumentException('Parent ID must be a number');
        }

        return new static(
            menu: (string) $data['menu'],
            title: (string) $data['title'],
            link: (string) $data['link'],
            description: $data['description'] ?? null,
            attrTitle: $data['attr_title'] ?? null,
            target: $data['target'] ?? null,
            classes: $data['classes'] ?? null,
            position: isset($data['position']) ? (int) $data['position'] : null,
            parentId: isset($data['parent_id']) ? (int) $data['parent_id'] : null,
            porcelain: $data['porcelain'] ?? false
        );
    }

    /**
     * Mengkonversi objek ke array asosiatif yang sesuai dengan argumen WP-CLI
     *
     * @return array Argumen dalam bentuk array asosiatif
     */
    public function toArray(): array
    {
        $args = [
            'description' => $this->description,
            'attr-title'  => $this->attrTitle,
            'target'      => $this->target,
            'classes'     => $this->classes,
            'position'    => $this->position,
            'parent-id'   => $this->parentId,
            'porcelain'   => $this->porcelain,
        ];

        // Hapus nilai null agar tidak mempengaruhi command
        $args = array_filter($args, function ($value) {
            return $value !== null;
        });

        return $args;
    }
}
