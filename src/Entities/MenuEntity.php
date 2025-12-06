<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use WP_Term;

final class MenuEntity
{
    /**
     * Mencari menu berdasarkan ID
     *
     * @param int $id ID menu yang akan dicari
     * @return WP_Term|null Instance WP_Term jika menu ditemukan, null jika tidak
     */
    public static function findById(int $id): ?WP_Term
    {
        $menu = wp_get_nav_menu_object($id);

        return $menu ?: null;
    }

    /**
     * Mencari menu berdasarkan nama slug
     *
     * @param string $slug Nama slug menu yang akan dicari
     * @return WP_Term|null Instance WP_Term jika menu ditemukan, null jika tidak
     */
    public static function findBySlug(string $slug): ?WP_Term
    {
        $menu = wp_get_nav_menu_object($slug);

        return $menu ?: null;
    }

    /**
     * Mengambil semua menu yang tersedia
     *
     * @return array Daftar menu dalam format array
     */
    public static function findAll(): array
    {
        return wp_get_nav_menus();
    }

    /**
     * Memeriksa apakah menu dengan ID tertentu ada
     *
     * @param int $id ID menu yang akan diperiksa
     * @return bool True jika menu ditemukan, false jika tidak
     */
    public static function exists(int $id): bool
    {
        $menu = wp_get_nav_menu_object($id);

        return $menu !== false;
    }

    /**
     * Membuat menu baru
     *
     * @param string $name Nama menu yang akan dibuat
     * @return int|false ID menu yang dibuat, false jika gagal
     */
    public static function create(string $name)
    {
        return wp_create_nav_menu($name);
    }

    /**
     * Menghapus menu berdasarkan ID
     *
     * @param int $id ID menu yang akan dihapus
     * @return bool True jika berhasil dihapus, false jika gagal
     */
    public static function delete(int $id): bool
    {
        return wp_delete_nav_menu($id);
    }
}
