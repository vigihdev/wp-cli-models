<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuEntityDto;
use WP;
use WP_Error;
use WP_Term;

final class MenuEntity
{
    /**
     * Mengambil menu berdasarkan ID, nama slug atau objek menu
     *
     * @param int|string $menu ID, nama slug atau objek menu yang akan dicari
     * @return MenuEntityDto|null Instance MenuEntityDto jika menu ditemukan, null jika tidak
     */
    public static function get(int|string $menu): ?MenuEntityDto
    {
        $menu = wp_get_nav_menu_object($menu);
        return $menu instanceof WP_Term ? MenuEntityDto::fromQuery($menu) : null;
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
     * Memeriksa apakah menu dengan ID, nama slug atau objek menu tertentu ada  
     * 
     * @param int|string $menu ID, nama slug atau objek menu yang akan dihapus
     * @return bool True jika menu ditemukan, false jika tidak
     */
    public static function exists(int|string $menu): bool
    {
        $menu = wp_get_nav_menu_object($menu);

        return $menu instanceof WP_Term;
    }


    /**
     * Membuat menu baru
     *
     * @param string $name Nama menu yang akan dibuat
     * @param array $menuData Data tambahan untuk menu (opsional)
     * @return int|WP_Error ID menu yang dibuat, WP_Error jika terjadi kesalahan
     */
    public static function create(string $name, array $menuData = []): int|WP_Error
    {
        if (self::exists($name)) {
            return new WP_Error(409, __('Menu already exists.'));
        }

        if (!empty($menuData)) {
            $menu = wp_create_nav_menu($name);
            if (!is_wp_error($menu)) {

                if (isset($menuData['location'])) {
                    $location = $menuData['location'];
                    unset($menuData['location']);
                    self::assignLocation($menu, $location);
                }

                return wp_update_nav_menu_object($menu, $menuData);
            }

            return $menu;
        }
        return wp_create_nav_menu($name);
    }

    private static function assignLocation(int $menu_id, string $location_slug): bool
    {
        $slug = sanitize_title($location_slug);
        $location_slug = str_replace('-', '_', $slug);
        $locations = get_theme_mod('nav_menu_locations', []);

        $locations[$location_slug] = $menu_id;
        return set_theme_mod('nav_menu_locations', $locations);
    }

    /**
     * Menghapus menu berdasarkan ID
     *
     * @param int|string $menu ID, nama slug atau objek menu yang akan dihapus
     * @return bool|WP_Error True jika berhasil dihapus, WP_Error jika terjadi kesalahan, false jika gagal
     */
    public static function delete(int|string $menu): bool|WP_Error
    {
        return wp_delete_nav_menu($menu);
    }
}
