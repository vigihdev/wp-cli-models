<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuEntityDto;
use WP_Error;
use WP_Term;

final class MenuEntity
{
    /**
     * @return MenuEntityDto|null Instance WP_Term jika menu ditemukan, null jika tidak
     */
    public static function get(int|string|WP_Term $menu): ?MenuEntityDto
    {
        $menu = wp_get_nav_menu_object($menu);
        return MenuEntityDto::fromQuery($menu) ?: null;
    }


    /**
     * Mengambil semua menu yang tersedia
     *
     * @return Collection<MenuEntityDto> Daftar menu dalam format array
     */
    public static function lists(): Collection
    {
        $data = wp_get_nav_menus();
        $data = array_map(fn($v) => MenuEntityDto::fromQuery($v), $data);
        return new Collection(data: $data);
    }

    /**
     * 
     * @param int|string|WP_Term $menu ID, nama slug atau objek menu yang akan dihapus
     * @return bool True jika menu ditemukan, false jika tidak
     */
    public static function exists(int|string|WP_Term $menu): bool
    {
        $menu = wp_get_nav_menu_object($menu);

        return $menu instanceof WP_Term;
    }


    /**
     * Membuat menu baru
     *
     * @param string $name Nama menu yang akan dibuat
     * @return int|false ID menu yang dibuat, false jika gagal
     */
    public static function create(string $name): int|false
    {
        if (self::exists($name)) {
            return false;
        }
        return wp_create_nav_menu($name);
    }

    /**
     * Menghapus menu berdasarkan ID
     *
     * @param int|string|WP_Term $menu ID, nama slug atau objek menu yang akan dihapus
     * @return bool|WP_Error True jika berhasil dihapus, WP_Error jika terjadi kesalahan, false jika gagal
     */
    public static function delete(int|string|WP_Term $menu): bool|WP_Error
    {
        return wp_delete_nav_menu($menu);
    }
}
