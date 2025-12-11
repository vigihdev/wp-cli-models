<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Fields;

use Vigihdev\WpCliModels\Contracts\Able\WpAbleInterface;
use Vigihdev\WpCliModels\Contracts\Fields\MenuItemFieldInterface;

/**
 * Class MenuItemFieldDto
 *
 * DTO untuk menyimpan dan mengakses struktur data item menu dasar di WP-CLI
 */
final class MenuItemFieldDto extends BaseFieldDto implements MenuItemFieldInterface, WpAbleInterface
{
    /**
     * Membuat instance objek MenuItemFieldDto dengan parameter yang ditentukan
     *
     * @param string      $type  Tipe menu item
     * @param string|null $label Label untuk menu item
     * @param string|null $title Title untuk menu item
     * @param string|null $url   URL untuk menu item
     */
    public function __construct(
        private readonly string $type,
        private readonly ?string $label,
        private readonly ?string $title,
        private readonly ?string $url,
    ) {}

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

    public function toWpFormat(): array
    {
        $mapping = [
            'type'        => 'menu-item-type',
            'title'       => 'menu-item-title',
            'label'       => 'menu-item-title',
            'url'         => 'menu-item-url',
            'object_id'   => 'menu-item-object-id',
            'object'      => 'menu-item-object',
            'parent_id'   => 'menu-item-parent-id',
            'classes'     => 'menu-item-classes',
            'target'      => 'menu-item-target',
            'attr_title'  => 'menu-item-attr-title',
        ];

        $data = $this->toArray();
        $wpData = [];

        foreach ($data as $key => $value) {
            if (isset($mapping[$key])) {
                $wpData[$mapping[$key]] = $value;
            }
        }

        // Default values
        $wpData['menu-item-status'] = 'publish';

        return $wpData;
    }

    /**
     * Mengkonversi objek MenuItemFieldDto menjadi array
     *
     * @return array<string, mixed> Array asosiatif yang berisi data menu item
     */
    public function toArray(): array
    {
        return array_filter([
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
            type: (string) ($data['type'] ?? ''),
            label: isset($data['label']) ? (string) $data['label'] : null,
            title: isset($data['title']) ? (string) $data['title'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
