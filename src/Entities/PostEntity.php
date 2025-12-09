<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use WP_Post;
use WP_Error;
use WP_Query;

final class PostEntity
{
    /**
     * Mencari post berdasarkan ID
     *
     * @param int $postId ID post yang akan dicari
     * @param string $output Tipe output (OBJECT, ARRAY_A, ARRAY_N)
     * @param string $filter Type filter
     * @return WP_Post|null Instance WP_Post jika post ditemukan, null jika tidak
     */
    public static function findById(int $postId, string $output = \OBJECT, string $filter = 'raw'): ?WP_Post
    {
        $post = get_post($postId, $output, $filter);

        if (!$post || is_wp_error($post)) {
            return null;
        }

        return $post;
    }

    /**
     * Mengambil post berdasarkan judul
     * Menemukan post berdasarkan title (exact match).
     *
     * @param string $postTitle Judul post yang akan dicari
     * @param string $output Tipe output (OBJECT, ARRAY_A, ARRAY_N)
     * @param string $filter Type filter
     * @param array $postStatus default 'publish', 'draft', 'pending', 'future', 'private'
     * @return WP_Post|null Instance WP_Post jika post ditemukan, null jika tidak
     */
    public static function findByTitle(
        string $postTitle,
        string $output = OBJECT,
        string $filter = 'raw',
        array $postStatus = ['publish', 'draft', 'pending', 'future', 'private']
    ): ?WP_Post {
        global $wpdb;

        $placeholders = implode(', ', array_fill(0, count($postStatus), '%s'));

        $sql = "
            SELECT ID 
            FROM $wpdb->posts 
            WHERE post_title = %s
            AND post_status IN ($placeholders)
            LIMIT 1
        ";

        $params = array_merge([$postTitle], $postStatus);

        $postId = $wpdb->get_var($wpdb->prepare($sql, ...$params));

        return $postId ? get_post($postId, $output, $filter) : null;
    }

    /**
     * Mencari post berdasarkan nama slug
     * Menemukan post berdasarkan post_name (slug) dengan pencocokan persis.
     *
     * @param string $postName Nama slug post yang akan dicari
     * @param string $output Tipe output (OBJECT, ARRAY_A, ARRAY_N)
     * @param string $filter Jenis filter untuk hasil post
     * @param array $postStatus Daftar status post yang akan dicari, default publish, draft, pending, future, private
     * @return WP_Post|null Instance WP_Post jika post ditemukan, null jika tidak ada yang cocok
     */
    public static function findByName(
        string $postName,
        string $output = OBJECT,
        string $filter = 'raw',
        array $postStatus = ['publish', 'draft', 'pending', 'future', 'private']
    ): ?WP_Post {
        global $wpdb;

        $placeholders = implode(', ', array_fill(0, count($postStatus), '%s'));

        $sql = "
            SELECT ID 
            FROM $wpdb->posts 
            WHERE post_name = %s
            AND post_status IN ($placeholders)
            LIMIT 1
        ";

        $params = array_merge([$postName], $postStatus);

        $postId = $wpdb->get_var($wpdb->prepare($sql, ...$params));

        return $postId ? get_post($postId, $output, $filter) : null;
    }

    /**
     * Mengambil posts berdasarkan kriteria tertentu
     *
     * @param array $args Argumen untuk query posts
     * @return array Daftar posts
     */
    public static function find(array $args = []): array
    {
        $defaults = [
            'post_type' => 'any',
            'post_status' => 'publish',
            'posts_per_page' => 10,
        ];

        $queryArgs = wp_parse_args($args, $defaults);
        $query = new WP_Query($queryArgs);

        return $query->posts;
    }

    /**
     * Mengambil satu post berdasarkan kriteria tertentu
     *
     * @param array $args Argumen untuk query posts
     * @return WP_Post|null Instance WP_Post jika post ditemukan, null jika tidak
     */
    public static function findOne(array $args = []): ?WP_Post
    {
        $args['posts_per_page'] = 1;
        $posts = self::find($args);

        return !empty($posts) ? $posts[0] : null;
    }

    /**
     * Membuat post baru
     *
     * @param array $postData Data post yang akan dibuat
     * @return int|WP_Error ID post yang dibuat atau WP_Error jika gagal
     */
    public static function create(array $postData)
    {
        return wp_insert_post($postData, true);
    }

    /**
     * Mengupdate post yang ada
     *
     * @param int $postId ID post yang akan diupdate
     * @param array $postData Data post yang akan diupdate
     * @return int|WP_Error ID post yang diupdate atau WP_Error jika gagal
     */
    public static function update(int $postId, array $postData)
    {
        $postData['ID'] = $postId;
        return wp_update_post($postData, true);
    }

    /**
     * Menghapus post
     *
     * @param int $postId ID post yang akan dihapus
     * @param bool $forceDelete Apakah akan dihapus permanen (default: false)
     * @return mixed Hasil penghapusan post
     */
    public static function delete(int $postId, bool $forceDelete = false)
    {
        return wp_delete_post($postId, $forceDelete);
    }

    /**
     * Memeriksa apakah post dengan ID tertentu ada
     *
     * @param int $postId ID post yang akan diperiksa
     * @return bool True jika post ditemukan, false jika tidak
     */
    public static function exists(int $postId): bool
    {
        $post = get_post($postId);
        return ($post && !is_wp_error($post) && $post->post_status !== 'trash');
    }

    /**
     * Mengecek apakah title sudah ada (true jika ada).
     */
    public static function existsByTitle(string $postTitle): bool
    {
        return (bool) self::findByTitle($postTitle);
    }


    /**
     * Mengecek apakah slug sudah ada (true jika ada).
     */
    public static function existsByName(string $postName): bool
    {
        return (bool) self::findByName($postName);
    }

    /**
     * Mengambil post berdasarkan slug
     *
     * @param string $slug Slug post
     * @param string $postType Tipe post (default: 'post')
     * @return WP_Post|null Instance WP_Post jika post ditemukan, null jika tidak
     */
    public static function findBySlug(string $slug, string $postType = 'post'): ?WP_Post
    {
        $args = [
            'name' => $slug,
            'post_type' => $postType,
            'post_status' => 'publish',
            'posts_per_page' => 1,
        ];

        $posts = self::find($args);
        return !empty($posts) ? $posts[0] : null;
    }

    /**
     * Menghitung jumlah posts berdasarkan kriteria
     *
     * @param array $args Argumen untuk query posts
     * @return int Jumlah posts
     */
    public static function count(array $args = []): int
    {
        $args['posts_per_page'] = 1;
        $query = new WP_Query($args);
        return $query->found_posts;
    }
}
