<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuItemEntityDto;
use WP_Error;


final class MenuItemEntity
{

    public static function findOne(int $postId, int|string $menuId): ?MenuItemEntityDto
    {

        $menu = self::get($menuId)
            ->filter(fn($dto) => $dto->getId() === $postId)
            ->first();

        return $menu;
    }

    /**
     * Mengambil item menu berdasarkan menu dan mengembalikan dalam bentuk collection
     *
     * @param int|string $menu ID, nama slug atau objek menu yang akan dicari
     * @param array $args Argumen tambahan untuk mengambil menu item
     * @return Collection<MenuItemEntityDto> Koleksi item menu dalam bentuk DTO
     */
    public static function get(int|string $menu, array $args = []): Collection
    {
        $array_menu = wp_get_nav_menu_items($menu, $args);

        if (empty($array_menu)) {
            return new Collection(data: []);
        }

        $data = array_map('wp_setup_nav_menu_item', $array_menu);
        $data = array_map(fn($v) => MenuItemEntityDto::fromQuery($v), $data);
        return new Collection(data: $data);
    }

    /**
     * Mencari item menu berdasarkan tipe dan judul
     *
     * @param int|string $menu ID, nama slug atau objek menu yang akan dicari
     * @param string $type Tipe menu item yang akan dicari
     * @param string $title Judul menu item yang akan dicari
     * @return MenuItemEntityDto|null Instance MenuItemEntityDto jika ditemukan, null jika tidak
     */
    public static function getTypeTitle(int|string $menu, string $type, string $title): ?MenuItemEntityDto
    {
        $items = self::get($menu)
            ->filter(fn($dto) => strtolower($dto->getTitle()) === strtolower($title) && $dto->getType() === $type);
        return $items->count() > 0 ? $items->first() : null;
    }


    /**
     * Memeriksa apakah menu item dengan tipe dan judul tertentu ada
     *
     * @param int|string $menu ID, nama slug atau objek menu yang akan dicari
     * @param string $type Tipe menu item yang akan diperiksa
     * @param string $title Judul menu item yang akan diperiksa
     * @return bool True jika menu item ditemukan, false jika tidak
     */
    public static function existByTitle(int|string $menu, string $type, string $title): bool
    {
        $items = self::get($menu)
            ->filter(fn(MenuItemEntityDto $dto) => strtolower($dto->getTitle()) === strtolower($title) && $dto->getType() === $type);
        return (bool) current($items);
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

    private static function update(int $id, array $menu_item_data) {}

    /**
     * Memeriksa apakah menu item dengan tipe dan judul tertentu ada
     *
     * @param int|string $menu ID, nama slug atau objek menu yang akan dicari
     * @param string $type Tipe menu item yang akan diperiksa
     * @param string $title Judul menu item yang akan diperiksa
     * @return bool True jika menu item ditemukan, false jika tidak
     */
    public static function exists(int|string $menu, string $type, string $title): bool
    {
        $items = self::get($menu)
            ->filter(fn($dto) => strtolower($dto->getTitle()) === strtolower($title) && $dto->getType() === $type);
        return (bool) current($items);
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
}
