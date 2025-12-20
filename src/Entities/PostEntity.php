<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use WP_Post;
use WP_Error;
use WP_Query;
use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Post\PostEntityDto;

final class PostEntity
{


    /**
     * Mengambil post berdasarkan ID
     *
     * @param int $postId ID post yang akan dicari
     * @return PostEntityDto|null Instance PostEntityDto jika post ditemukan, null jika tidak
     */
    public static function get(int $postId): ?PostEntityDto
    {
        $post = get_post($postId);

        if (!$post || is_wp_error($post)) {
            return null;
        }

        return PostEntityDto::fromQuery($post);
    }

    /**
     * Mencari post berdasarkan kriteria tertentu
     *
     * @param int $limit Jumlah post yang akan diambil (default: 50)
     * @param int $offset Offset post yang akan diambil (default: 0)
     * @param array $args Argumen kueri post (default: [])
     * @return Collection<PostEntityDto> Daftar post dalam format $arrayName = array();
     */
    public static function filter(int $limit = 50, int $offset = 0, array $args = []): Collection
    {
        $defaults = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'offset' => $offset,
        ];

        $queryArgs = wp_parse_args($args, $defaults);
        $query = new WP_Query($queryArgs);

        if (!$query->have_posts()) {
            return new Collection([]);
        }

        $data = array_map(
            fn($post) => PostEntityDto::fromQuery($post),
            $query->get_posts()
        );

        return new Collection($data);
    }

    /**
     * Mencari post berdasarkan ID
     *
     * @param int $postId ID post yang akan dicari
     * @return PostEntityDto|null Instance PostEntityDto jika post ditemukan, null jika tidak
     */
    public static function findById(int $postId): ?PostEntityDto
    {
        $post = get_post($postId);

        if (!$post || is_wp_error($post)) {
            return null;
        }

        return PostEntityDto::fromQuery($post);
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
     * ```php
     * $args = [
     *   'post_type' => 'post'
     *   'post_status' => 'publish',
     *   'posts_per_page' => 50,
     *   'offset' => 0,
     * ];
     * $posts = PostEntity::find($args);
     * ```
     * @param array $args Argumen untuk query posts, default: []
     * @return Collection<PostEntityDto> Daftar collection post dalam format PostEntityDto
     */
    public static function find(array $args = []): Collection
    {

        $queryArgs = wp_parse_args($args);
        $query = new WP_Query($queryArgs);

        $posts = array_filter($query->get_posts(), fn($post) => $post instanceof WP_Post);
        $data = array_map(
            fn($post) => PostEntityDto::fromQuery($post),
            $posts
        );

        return new Collection($data);
    }

    /**
     * Membuat post baru 
     * 
     * ```php
     * $postData = [
     *   'ID' => 2,
     *   'post_author' => 1,
     *   'post_date' => '2023-12-01 12:00:00',
     *   'post_date_gmt' => '2023-12-01 12:00:00',
     *   'post_content' => 'Ini adalah isi post',
     *   'post_content_filtered' => '',
     *   'post_title' => 'Judul Post',
     *   'post_excerpt' => 'Ringkasan post',
     *   'post_status' => 'publish',
     *   'post_type' => 'post',
     *   'comment_status' => 'open',
     *   'ping_status' => 'open',
     *   'post_password' => '',
     *   'post_name' => 'judul-post',
     *   'to_ping' => '',
     *   'pinged' => '',
     *   'post_parent' => 0,
     *   'menu_order' => 0,
     *   'post_mime_type' => '',
     *   'guid' => 'https://example.com/judul-post/',     
     *   'import_id' => 0,
     *   'post_category' => [1, 2, 3],
     *   'tags_input' => ['tag1', 'tag2'],
     *   'tax_input' => [
     *     'category' => [1, 2, 3],
     *     'post_tag' => ['tag1', 'tag2'],
     *   ],
     *   'meta_input' => [
     *     'meta_key1' => 'meta_value1',
     *     'meta_key2' => 'meta_value2',
     *   ],
     *   'page_template' => 'page-template.php',
     * ];
     * ```
     * @param array $postData Data post yang akan dibuat
     * @return int|WP_Error ID post yang dibuat atau WP_Error jika gagal
     */
    public static function create(array $postData): int|WP_Error
    {
        return wp_insert_post($postData, true);
    }

    /**
     * Mengupdate post yang ada
     * 
     * ```php
     * $postData = [
     *   'ID' => 2,
     *   'post_author' => 1,
     *   'post_date' => '2023-12-01 12:00:00',
     *   'post_date_gmt' => '2023-12-01 12:00:00',
     *   'post_content' => 'Ini adalah isi post',
     *   'post_content_filtered' => '',
     *   'post_title' => 'Judul Post',
     *   'post_excerpt' => 'Ringkasan post',
     *   'post_status' => 'publish',
     *   'post_type' => 'post',
     *   'comment_status' => 'open',
     *   'ping_status' => 'open',
     *   'post_password' => '',
     *   'post_name' => 'judul-post',
     *   'to_ping' => '',
     *   'pinged' => '',
     *   'post_parent' => 0,
     *   'menu_order' => 0,
     *   'post_mime_type' => '',
     *   'guid' => 'https://example.com/judul-post/',     
     *   'import_id' => 0,
     *   'post_category' => [1, 2, 3],
     *   'tags_input' => ['tag1', 'tag2'],
     *   'tax_input' => [
     *     'category' => [1, 2, 3],
     *     'post_tag' => ['tag1', 'tag2'],
     *   ],
     *   'meta_input' => [
     *     'meta_key1' => 'meta_value1',
     *     'meta_key2' => 'meta_value2',
     *   ],
     *   'page_template' => 'page-template.php',
     * ];
     * ```
     * @see https://developer.wordpress.org/reference/functions/wp_update_post/
     * @see PostEntity::create()
     * @param int $postId ID post yang akan diupdate
     * @param array $postData Data post yang akan diupdate
     * @return int|WP_Error ID post yang diupdate atau WP_Error jika gagal
     */
    public static function update(int $postId, array $postData): int|WP_Error
    {
        $postData['ID'] = $postId;
        return wp_update_post($postData, true);
    }

    /**
     * Menghapus post
     *
     * @param int $postId ID post yang akan dihapus
     * @param bool $forceDelete Apakah akan dihapus permanen (default: false)
     * @return WP_Post|false|null Instance WP_Post jika post dihapus, false jika gagal, null jika post tidak ada
     */
    public static function delete(int $postId, bool $forceDelete = false): WP_Post|false|null
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
}
