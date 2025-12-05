<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args;

use Vigihdev\WpCliModels\Contracts\Args\MenuArgsInterface;

/**
 * Class MenuArgsDto
 *
 * DTO untuk menyimpan dan mengakses data argumen menu
 */
final class MenuArgsDto implements MenuArgsInterface
{
    /**
     * Membuat instance objek MenuArgsDto dengan parameter yang ditentukan
     *
     * @param string $name Nama menu yang akan dibuat
     * @param ?string $slug Slug URL untuk menu
     * @param ?string $description Deskripsi tentang menu
     * @param ?string $location Lokasi tampilan menu
     */
    public function __construct(
        private readonly string $name,
        private readonly ?string $slug = null,
        private readonly ?string $description = null,
        private readonly ?string $location = null
    ) {}

    /**
     * Mendapatkan nama menu
     *
     * @return string Nama menu
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Mendapatkan slug menu
     *
     * @return ?string Slug menu atau null jika tidak diset
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Mendapatkan deskripsi menu
     *
     * @return ?string Deskripsi menu atau null jika tidak diset
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Mendapatkan lokasi menu
     *
     * @return ?string Lokasi menu atau null jika tidak diset
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
    public static function FromArray(array $data): static
    {
        if (!isset($data['name'])) {
            throw new \InvalidArgumentException('Name is required to create MenuArgsDto');
        }

        return new static(
            $data['name'],
            $data['slug'] ?? null,
            $data['description'] ?? null,
            $data['location'] ?? null
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
