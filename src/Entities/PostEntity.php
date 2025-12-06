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
     *
     * @param string $postTitle Judul post yang akan dicari
     * @param string $output Tipe output (OBJECT, ARRAY_A, ARRAY_N)
     * @param string $filter Type filter
     * @return WP_Post|null Instance WP_Post jika post ditemukan, null jika tidak
     */
    public static function findByTitle(string $postTitle, string $output = \OBJECT, string $filter = 'raw'): ?WP_Post
    {
        $args = [
            'post_type' => 'any',
            'post_status' => 'any',
            'title' => $postTitle,
            'posts_per_page' => 1,
        ];

        $posts = get_posts($args);

        if (empty($posts)) {
            return null;
        }

        // Pastikan judul benar-benar cocok (case sensitive)
        foreach ($posts as $post) {
            if ($post->post_title === $postTitle) {
                return get_post($post->ID, $output, $filter);
            }
        }

        return null;
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
            'post_type' => 'post',
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
