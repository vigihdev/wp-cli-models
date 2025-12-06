<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Args\Menu\MenuArgsInterface;
use Vigihdev\WpCliModels\DTOs\Args\BaseArgsDto;

/**
 * Class MenuArgsDto
 *
 * DTO untuk menyimpan dan mengakses data menu di WordPress
 */
final class MenuArgsDto extends BaseArgsDto implements MenuArgsInterface
{
    /**
     * Membuat instance objek MenuArgsDto dengan parameter yang ditentukan
     *
     * @param string $name Nama menu
     * @param string|null $slug Slug menu
     * @param string|null $description Deskripsi menu
     * @param string|null $location Lokasi menu
     */
    public function __construct(
        private readonly string $name,
        private readonly ?string $slug = null,
        private readonly ?string $description = null,
        private readonly ?string $location = null,
    ) {}

    /**
     * Mendapatkan nama dari menu
     *
     * @return string Nama menu
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Mendapatkan slug dari menu
     *
     * @return string Slug menu
     */
    public function getSlug(): string
    {
        return $this->slug ?? sanitize_title($this->name);
    }

    /**
     * Mendapatkan deskripsi dari menu
     *
     * @return string|null Deskripsi menu
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Mendapatkan lokasi dari menu
     *
     * @return string|null Lokasi menu
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }


    /**
     * Membuat instance objek MenuArgsDto dari array data
     *
     * @param array $data Data array yang berisi informasi argumen menu
     * @return static Instance objek MenuArgsDto yang dibuat dari data array
     * @throws InvalidArgumentException Jika nama menu tidak disediakan dalam data
     */
    public static function fromArray(array $data): static
    {
        if (!isset($data['name'])) {
            throw new \InvalidArgumentException('Name is required to create MenuArgsDto');
        }

        return new static(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            location: $data['location'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'menu-name' => $this->name,
            'menu-slug' => $this->slug ?? sanitize_title($this->name),
            'menu-description' => $this->description,
            'menu-location' => $this->location,
        ];
    }
}
