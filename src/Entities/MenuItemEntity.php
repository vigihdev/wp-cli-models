<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuItemEntityDto;
use WP_Error;


final class MenuItemEntity
{

    /**
     * Mengecek apakah label sudah ada di dalam menu tertentu.
     * 
     * @param int|string|\WP_Term $menu ID, Slug, atau Object Menu.
     * @param string $label Label yang ingin dicek.
     * @return bool Jika label sudah ada di dalam menu, maka akan mengembalikan true.
     */
    public static function existsByLabel(int|string|\WP_Term $menu, string $label): bool
    {
        $collection = self::findByMenuLabel($menu, $label);
        return $collection->count() > 0;
    }

    /**
     * Mengecek apakah URL sudah ada di dalam menu tertentu.
     * 
     * @param int|string|\WP_Term $menu ID, Slug, atau Object Menu.
     * @param string $url URL yang ingin dicek.
     * @return bool Jika URL sudah ada di dalam menu, maka akan mengembalikan true.
     */
    public static function existsByUrl(int|string|\WP_Term $menu, string $url): bool
    {
        $collection = self::findByMenuUrl($menu, $url);
        return $collection->count() > 0;
    }

    /**
     * Mencari item menu berdasarkan label.
     * 
     * @param int|string|\WP_Term $menu ID, Slug, atau Object Menu.
     * @param string $label Label yang ingin dicari.
     * @return Collection<MenuItemEntityDto> Koleksi item menu yang memiliki label yang sesuai.
     */
    public static function findByMenuLabel(int|string|\WP_Term $menu, string $label): Collection
    {
        return self::get($menu)
            ->filter(fn($item) => strtolower($item->getTitle()) === strtolower($label));
    }

    /**
     * Mencari item menu berdasarkan URL.
     * 
     * @param int|string|\WP_Term $menu ID, Slug, atau Object Menu.
     * @param string $url URL yang ingin dicari.
     * @return Collection<MenuItemEntityDto> Koleksi item menu yang memiliki URL yang sesuai.
     */
    public static function findByMenuUrl(int|string|\WP_Term $menu, string $url): Collection
    {
        $targetUrl = self::normalizeUrl($url);

        return self::get($menu)->filter(function ($item) use ($targetUrl) {
            $currentUrl = self::normalizeUrl($item->getUrl());

            return $currentUrl === $targetUrl;
        });
    }


    /**
     * Mengambil item menu berdasarkan menu dan mengembalikan dalam bentuk collection
     *
     * @param int|string|\WP_Term $menu ID, Slug, atau Object Menu.
     * @param array $args Argumen tambahan untuk mengambil menu item
     * @return Collection<MenuItemEntityDto> Koleksi item menu dalam bentuk DTO
     */
    public static function get(int|string|\WP_Term $menu, array $args = []): Collection
    {
        $items = wp_get_nav_menu_items($menu, $args);

        if (empty($items) || is_wp_error($items)) {
            return new Collection([]);
        }

        $mappedData = array_map(function ($postObject) {
            $menuItem = wp_setup_nav_menu_item($postObject);
            return MenuItemEntityDto::fromQuery($menuItem);
        }, $items);

        return new Collection($mappedData);
    }

    /**
     * Membuat item menu baru berdasarkan nama menu dan data yang diberikan
     *
     * @param string $menuName Nama menu tempat item menu akan dibuat
     * @param array $menu_item_data Data item menu yang akan dibuat (contoh: ['title' => 'Home', 'type' => 'post_type', 'url' => 'https://example.com'])
     * @return int|WP_Error ID item menu yang berhasil dibuat atau WP_Error jika gagal
     */
    public static function create(string $menuName, array $menu_item_data): int|WP_Error
    {
        if (! MenuEntity::exists($menuName)) {
            return new WP_Error(code: 404, message: "Menu '{$menuName}' tidak ditemukan");
        }

        if (empty($menu_item_data)) {
            return new WP_Error(code: 400, message: "Data item menu tidak boleh kosong");
        }

        $menuId = MenuEntity::get($menuName)->getTermId();
        return wp_update_nav_menu_item($menuId, 0, $menu_item_data);
    }

    /**
     * Menghapus menu item berdasarkan ID
     *
     * @param int $postId ID menu item yang akan dihapus
     * @return bool True jika berhasil dihapus, false jika gagal
     */
    public static function delete(int $postId): bool
    {

        $post = get_post($postId);

        if (!$post) {
            return false;
        }

        if ($post->post_type !== 'nav_menu_item') {
            return false;
        }

        return (bool) wp_delete_post($postId, true);
    }

    /**
     * Normalisasi URL untuk memastikan format URL yang konsisten
     *
     * @param string $url URL yang akan dinormalisasi
     * @return string URL yang sudah dinormalisasi
     */
    private static function normalizeUrl(string $url): string
    {
        $parts = parse_url($url);

        $host = isset($parts['host']) ? $parts['host'] : '';
        $path = isset($parts['path']) ? $parts['path'] : '';

        $cleanUrl = $host . $path;
        return untrailingslashit($cleanUrl);
    }
}
