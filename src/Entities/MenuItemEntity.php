<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuItemEntityDto;
use WP_Error;


final class MenuItemEntity
{

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
     * @param array $data Data item menu yang akan dibuat
     * @return int|WP_Error ID item menu yang berhasil dibuat atau WP_Error jika gagal
     */
    public static function create(string $menuName, array $data): int|WP_Error
    {
        if (! MenuEntity::exists($menuName)) {
            return new WP_Error(code: 404, message: "Menu '{$menuName}' tidak ditemukan");
        }

        if (empty($data)) {
            return new WP_Error(code: 400, message: "Data item menu tidak boleh kosong");
        }

        $menuId = MenuEntity::get($menuName)->getTermId();
        return wp_update_nav_menu_item($menuId, 0, $data);
    }

    private static function update(int $id, array $data) {}

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
     * @param int|string $menu ID, nama slug atau objek menu yang akan dicari
     * @param string $type Tipe menu item yang akan dihapus
     * @param string $title Judul menu item yang akan dihapus
     * @return bool True jika berhasil dihapus, false jika gagal
     */
    public static function delete(int|string $menu, string $type, string $title): bool
    {
        if (self::exists($menu, $type, $title)) {
            return (bool) wp_delete_post(self::getTypeTitle($menu, $type, $title)->getId(), true);
        }
        return false;
    }
}
