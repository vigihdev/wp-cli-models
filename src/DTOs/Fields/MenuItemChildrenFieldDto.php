<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\MenuItemChildrenFieldInterface;

/**
 * Class MenuItemChildrenFieldDto
 *
 * DTO untuk menyimpan dan mengakses struktur data item menu dasar di WP-CLI
 */
final class MenuItemChildrenFieldDto extends BaseFieldDto implements MenuItemChildrenFieldInterface
{
    /**
     * Membuat instance objek MenuItemChildrenFieldDto dengan parameter yang ditentukan
     *
     * @param int      $parent
     * @param string      $type  Tipe menu item
     * @param string|null $label Label untuk menu item
     * @param string|null $title Title untuk menu item
     * @param string|null $url   URL untuk menu item
     */
    public function __construct(
        private readonly int $parent,
        private readonly string $type,
        private readonly ?string $label,
        private readonly ?string $title,
        private readonly ?string $url,
    ) {}

    public function getParent(): int
    {
        return $this->parent;
    }

    /**
     * Mendapatkan tipe dari menu item
     *
     * @return string Tipe menu item
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Mendapatkan label dari menu item
     *
     * @return string|null Label untuk menu item
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Mendapatkan title dari menu item
     *
     * @return string|null Title untuk menu item
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Mendapatkan URL dari menu item
     *
     * @return string|null URL untuk menu item
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Mengkonversi objek MenuItemFieldDto menjadi array
     *
     * @return array<string, mixed> Array asosiatif yang berisi data menu item
     */
    public function toArray(): array
    {
        return array_filter([
            'parent' => $this->getParent(),
            'type' => $this->getType(),
            'label' => $this->getLabel(),
            'title' => $this->getTitle(),
            'url' => $this->getUrl(),
        ], function ($value) {
            return $value !== null;
        });
    }

    /**
     * Membuat instance MenuItemFieldDto dari array data
     *
     * @param array<string, mixed> $data Data untuk membuat objek MenuItemFieldDto
     *
     * @return self Instance baru dari MenuItemFieldDto
     */
    public static function fromArray(array $data): static
    {
        return new self(
            parent: (int) ($data['parent'] ?? 0),
            type: (string) ($data['type'] ?? ''),
            label: isset($data['label']) ? (string) $data['label'] : null,
            title: isset($data['title']) ? (string) $data['title'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
