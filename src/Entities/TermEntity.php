<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\TermEntityDto;
use WP_Term;
use WP_Error;

final class TermEntity
{
    /**
     *
     * @return Collection<TermEntityDto>
     */
    public static function findAll(): Collection
    {
        $terms = get_terms();

        if (!$terms || is_wp_error($terms)) {
            return new Collection([]);
        }

        $data = array_map(fn($term) => TermEntityDto::fromQuery($term), $terms);
        return new Collection($data);
    }

    /**
     * Mencari term berdasarkan taxonomy
     *
     * @param string $name Nama term yang akan dicari
     * @return Collection<TermEntityDto> Koleksi term yang ditemukan
     */
    public static function getName(string $name): Collection
    {
        return self::findAll()
            ->filter(fn($term) => strtolower($term->getName()) === strtolower($name));
    }

    /**
     * Mencari term berdasarkan slug
     *
     * @param string $slug
     * @return Collection<TermEntityDto>
     */
    public static function getSlug(string $slug): Collection
    {
        return self::findAll()
            ->filter(fn($term) => strtolower($term->getSlug()) === strtolower($slug));
    }

    /**
     * Mencari term berdasarkan ID
     *
     * @param int $termId ID term yang akan dicari
     * @return TermEntityDto|null Instance WP_Term jika term ditemukan, null jika tidak
     */
    public static function getId(int $termId): ?TermEntityDto
    {
        return self::findAll()
            ->filter(fn($term) => $term->getTermId() === $termId)
            ?->first();
    }


    /**
     * Mencari term berdasarkan ID
     *
     * @param int $termId ID term yang akan dicari
     * @param string|null $taxonomy Nama taxonomy tempat mencari term (opsional)
     * @return TermEntityDto|null Instance WP_Term jika term ditemukan, null jika tidak
     */
    public static function findById(int $termId, ?string $taxonomy = null): ?TermEntityDto
    {
        $term = get_term($termId, $taxonomy);

        if (!$term || is_wp_error($term)) {
            return null;
        }

        return TermEntityDto::fromQuery($term);
    }

    /**
     * Mencari term berdasarkan slug
     *
     * @param string $slug Slug term yang akan dicari
     * @param string $taxonomy Nama taxonomy tempat mencari term
     * @return WP_Term|null Instance WP_Term jika term ditemukan, null jika tidak
     */
    public static function findBySlug(string $slug, string $taxonomy): ?WP_Term
    {
        $term = get_term_by('slug', $slug, $taxonomy);

        if (!$term || is_wp_error($term)) {
            return null;
        }

        return $term;
    }

    /**
     * Mencari term berdasarkan nama
     *
     * @param string $name Nama term yang akan dicari
     * @param string $taxonomy Nama taxonomy tempat mencari term
     * @return WP_Term|null Instance WP_Term jika term ditemukan, null jika tidak
     */
    public static function findByName(string $name, string $taxonomy): ?WP_Term
    {
        $term = get_term_by('name', $name, $taxonomy);

        if (!$term || is_wp_error($term)) {
            return null;
        }

        return $term;
    }

    /**
     * Mengambil semua terms dari taxonomy tertentu
     *
     * @param string $taxonomy Nama taxonomy
     * @param array $args Argumen tambahan untuk query terms
     * @return array Daftar terms dalam format array
     */
    public static function findAllByTaxonomy(string $taxonomy, array $args = []): array
    {
        $terms = get_terms(array_merge([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ], $args));

        if (is_wp_error($terms)) {
            return [];
        }

        return $terms;
    }

    /**
     * Membuat term baru
     *
     * @param string $term Nama term yang akan dibuat
     * @param string $taxonomy Nama taxonomy tempat membuat term
     * @param array $args Argumen tambahan untuk pembuatan term
     * @return array{term_id: int, term_taxonomy_id: int|string}|WP_Error Array dengan informasi term dan taxonomy, atau WP_Error jika gagal
     */
    public static function create(string $term, string $taxonomy, array $args = [])
    {
        $result = wp_insert_term($term, $taxonomy, $args);

        return $result;
    }

    /**
     * Memeriksa apakah term dengan ID tertentu ada
     *
     * @param int $termId ID term yang akan diperiksa
     * @param string $taxonomy Nama taxonomy tempat memeriksa term
     * @return bool True jika term ditemukan, false jika tidak
     */
    public static function exists(int $termId, string $taxonomy): bool
    {
        $term = get_term($termId, $taxonomy);

        return ($term && !is_wp_error($term));
    }
}
