<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Args\Menu\TermItemMenuArgsInterface;
use Vigihdev\WpCliModels\DTOs\Args\BaseArgsDto;

/**
 * Class TermItemMenuArgsDto
 *
 * DTO untuk menyimpan dan mengakses data perintah penambahan term item menu di WP-CLI
 */
final class TermItemMenuArgsDto extends BaseArgsDto implements TermItemMenuArgsInterface
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

    /**
     * Membuat instance objek TermItemMenuArgsDto dari array data menggunakan named arguments
     *
     * @param array $data Array data yang berisi informasi menu item
     * @return static Instance objek TermItemMenuArgsDto baru
     * @throws InvalidArgumentException Jika data yang diberikan tidak valid
     */
    public static function fromArray(array $data): static
    {
        // Validasi required fields
        if (empty($data['menu'])) {
            throw new \InvalidArgumentException('Menu identifier is required');
        }

        if (empty($data['taxonomy'])) {
            throw new \InvalidArgumentException('Taxonomy is required');
        }

        if (!isset($data['term_id']) || !is_numeric($data['term_id'])) {
            throw new \InvalidArgumentException('Valid term ID is required');
        }

        // Validasi tipe data untuk field numerik
        if (isset($data['position']) && !is_numeric($data['position'])) {
            throw new \InvalidArgumentException('Position must be a number');
        }

        if (isset($data['parent_id']) && !is_numeric($data['parent_id'])) {
            throw new \InvalidArgumentException('Parent ID must be a number');
        }

        return new static(
            menu: $data['menu'],
            taxonomy: $data['taxonomy'],
            termId: (int) $data['term_id'],
            title: $data['title'] ?? null,
            link: $data['link'] ?? null,
            description: $data['description'] ?? null,
            attrTitle: $data['attr_title'] ?? null,
            target: $data['target'] ?? null,
            classes: $data['classes'] ?? null,
            position: isset($data['position']) ? (int) $data['position'] : null,
            parentId: isset($data['parent_id']) ? (int) $data['parent_id'] : null
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
            'title'       => $this->title,
            'link'        => $this->link,
            'description' => $this->description,
            'attr-title'  => $this->attrTitle,
            'target'      => $this->target,
            'classes'     => $this->classes,
            'position'    => $this->position,
            'parent-id'   => $this->parentId,
        ];

        // Hapus nilai null agar tidak mempengaruhi command
        $args = array_filter($args, function ($value) {
            return $value !== null;
        });

        return $args;
    }
}
