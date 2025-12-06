<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

final class PostmetaEntity
{
    /**
     * Mengambil meta value berdasarkan post ID dan meta key
     *
     * @param int $postId ID post
     * @param string $metaKey Nama meta key
     * @param bool $single Apakah hanya mengambil satu value (default: true)
     * @return mixed Value dari meta field, atau array jika $single = false
     */
    public static function get(int $postId, string $metaKey, bool $single = true)
    {
        return get_post_meta($postId, $metaKey, $single);
    }

    /**
     * Mengambil semua meta data untuk post tertentu
     *
     * @param int $postId ID post
     * @return array Daftar semua meta data untuk post
     */
    public static function getAll(int $postId): array
    {
        return get_post_meta($postId);
    }

    /**
     * Menambahkan atau mengupdate post meta
     *
     * @param int $postId ID post
     * @param string $metaKey Nama meta key
     * @param mixed $metaValue Value untuk meta key
     * @param bool $unique Apakah meta key harus unik (default: false)
     * @return bool|int False jika gagal, meta ID jika berhasil menambah, true jika berhasil mengupdate
     */
    public static function update(int $postId, string $metaKey, $metaValue, bool $unique = false)
    {
        return update_post_meta($postId, $metaKey, $metaValue, $unique);
    }

    /**
     * Menghapus post meta
     *
     * @param int $postId ID post
     * @param string $metaKey Nama meta key
     * @param mixed $metaValue Spesifik value yang akan dihapus (opsional)
     * @return bool True jika berhasil, false jika gagal
     */
    public static function delete(int $postId, string $metaKey, $metaValue = ''): bool
    {
        return delete_post_meta($postId, $metaKey, $metaValue);
    }

    /**
     * Mengecek apakah post meta dengan key tertentu ada
     *
     * @param int $postId ID post
     * @param string $metaKey Nama meta key
     * @return bool True jika meta key ada, false jika tidak
     */
    public static function exists(int $postId, string $metaKey): bool
    {
        $meta = get_post_meta($postId, $metaKey, true);
        return !empty($meta) || $meta === '' || $meta === 0 || $meta === false;
    }

    /**
     * Mengambil post IDs berdasarkan meta key dan value
     *
     * @param string $metaKey Nama meta key
     * @param mixed $metaValue Value dari meta key (opsional)
     * @param array $args Argumen tambahan untuk query
     * @return array Daftar post IDs
     */
    public static function getPostIdsByMeta(string $metaKey, $metaValue = null, array $args = []): array
    {
        $queryArgs = array_merge([
            'post_type' => 'any',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => $metaKey,
                ]
            ]
        ], $args);

        if ($metaValue !== null) {
            $queryArgs['meta_query'][0]['value'] = $metaValue;
        }

        $query = new \WP_Query($queryArgs);
        return $query->posts;
    }

    /**
     * Mengupdate banyak post meta sekaligus
     *
     * @param int $postId ID post
     * @param array $metaData Associative array dengan key-value pairs
     * @return bool True jika semua berhasil diupdate, false jika salah satu gagal
     */
    public static function updateBulk(int $postId, array $metaData): bool
    {
        $results = [];
        foreach ($metaData as $metaKey => $metaValue) {
            $results[] = update_post_meta($postId, $metaKey, $metaValue);
        }

        // Return true jika tidak ada yang false
        return !in_array(false, $results, true);
    }
}
