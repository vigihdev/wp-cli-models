<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use WP_Post;
use WP_Term;

final class MenuItemEntity
{

    /**
     * Mengambil semua item menu berdasarkan menu
     *
     * @param int|string|WP_Term $menu ID menu, slug, atau objek WP_Term
     * @param array $args Argumen tambahan untuk mengambil menu item
     * @return array Daftar item menu
     */
    public static function getItems(int|string|WP_Term $menu, array $args = []): array
    {
        $array_menu = wp_get_nav_menu_items($menu, $args);

        if (empty($array_menu)) {
            return [];
        }

        // Setup nav menu items
        return array_map('wp_setup_nav_menu_item', $array_menu);
    }

    /**
     * Mengambil item menu tanpa parent (parent = 0)
     *
     * @param int|string|WP_Term $menu ID menu, slug, atau objek WP_Term
     * @param array $args Argumen tambahan untuk mengambil menu item
     * @return array Daftar item menu tanpa parent
     */
    public static function getItemsWithoutParent(int|string|WP_Term $menu, array $args = []): array
    {
        $all_items = self::getItems($menu, $args);

        if (empty($all_items)) {
            return [];
        }

        // Filter item yang tidak memiliki parent (parent = 0)
        return array_filter($all_items, function ($item) {
            return isset($item->menu_item_parent) && (int)$item->menu_item_parent === 0;
        });
    }

    /**
     * Mencari menu item berdasarkan ID
     *
     * @param int $id ID menu item yang akan dicari
     * @return WP_Post|null Instance WP_Post jika menu item ditemukan, null jika tidak
     */
    public static function findById(int $id): ?WP_Post
    {
        $menu_item = get_post($id);

        if (!$menu_item || $menu_item->post_type !== 'nav_menu_item') {
            return null;
        }

        return wp_setup_nav_menu_item($menu_item);
    }

    /**
     * Mengambil semua menu item berdasarkan menu ID
     *
     * @param int $menu_id ID menu
     * @param array $args Argumen tambahan untuk mengambil menu item
     * @return array Daftar menu item
     */
    public static function findByMenuId(int $menu_id, array $args = []): array
    {
        $default_args = [
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'post_type' => 'nav_menu_item',
            'post_status' => 'publish',
            'output' => ARRAY_A,
            'output_key' => 'menu_order',
            'nopaging' => true,
        ];

        $query_args = wp_parse_args($args, $default_args);

        $menu_items = wp_get_nav_menu_items($menu_id, $query_args);

        if (empty($menu_items)) {
            return [];
        }

        // Setup nav menu items
        return array_map('wp_setup_nav_menu_item', $menu_items);
    }

    /**
     * Memeriksa apakah menu item dengan ID tertentu ada
     *
     * @param int $id ID menu item yang akan diperiksa
     * @return bool True jika menu item ditemukan, false jika tidak
     */
    public static function exists(int $id): bool
    {
        $menu_item = get_post($id);

        return $menu_item && $menu_item->post_type === 'nav_menu_item';
    }

    /**
     * Menghapus menu item berdasarkan ID
     *
     * @param int $id ID menu item yang akan dihapus
     * @return bool True jika berhasil dihapus, false jika gagal
     */
    public static function delete(int $id): bool
    {
        return (bool) wp_delete_post($id, true);
    }
}
