<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\CategoryEntityDto;
use WP_Term;

final class CategoryEntity
{
    /**
     * @return CategoryEntityDto|null Instance WP_Term jika kategori ditemukan, null jika tidak
     */
    public static function get(int|string|WP_Term $category): ?CategoryEntityDto
    {
        $category = get_term($category);
        return CategoryEntityDto::fromQuery($category) ?: null;
    }


    /**
     * Mengambil semua kategori yang tersedia
     *
     * @return Collection<CategoryEntityDto> Daftar kategori dalam format array
     */
    public static function lists(): Collection
    {
        $data = get_categories();
        $data = array_map(fn($v) => CategoryEntityDto::fromQuery($v), $data);
        return new Collection(data: $data);
    }
}
