<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

final class TermTaxonomyEntity
{
    /**
     * Mencari term taxonomy berdasarkan ID
     *
     * @param int $termTaxonomyId ID term taxonomy yang akan dicari
     * @return object|null Object term taxonomy jika ditemukan, null jika tidak
     */
    public static function findById(int $termTaxonomyId): ?object
    {
        global $wpdb;

        $termTaxonomy = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id = %d",
            $termTaxonomyId
        ));

        return $termTaxonomy ?: null;
    }

    /**
     * Mencari term taxonomy berdasarkan term ID dan taxonomy
     *
     * @param int $termId ID term
     * @param string $taxonomy Nama taxonomy
     * @return object|null Object term taxonomy jika ditemukan, null jika tidak
     */
    public static function findByTermAndTaxonomy(int $termId, string $taxonomy): ?object
    {
        global $wpdb;

        $termTaxonomy = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->term_taxonomy} WHERE term_id = %d AND taxonomy = %s",
            $termId,
            $taxonomy
        ));

        return $termTaxonomy ?: null;
    }

    /**
     * Mengambil semua term taxonomies berdasarkan taxonomy
     *
     * @param string $taxonomy Nama taxonomy
     * @param array $args Argumen tambahan untuk query
     * @return array Daftar term taxonomies
     */
    public static function findAllByTaxonomy(string $taxonomy, array $args = []): array
    {
        global $wpdb;

        $defaults = [
            'hide_empty' => false,
            'orderby' => 'tt.term_taxonomy_id',
            'order' => 'ASC'
        ];

        $args = wp_parse_args($args, $defaults);

        $query = "SELECT tt.* FROM {$wpdb->term_taxonomy} tt WHERE tt.taxonomy = %s";

        if (!empty($args['hide_empty'])) {
            $query .= " AND tt.count > 0";
        }

        $query .= " ORDER BY " . $args['orderby'] . " " . $args['order'];

        $termTaxonomies = $wpdb->get_results($wpdb->prepare($query, $taxonomy));

        return $termTaxonomies ?: [];
    }

    /**
     * Menghitung jumlah term dalam taxonomy
     *
     * @param string $taxonomy Nama taxonomy
     * @return int Jumlah term dalam taxonomy
     */
    public static function countByTaxonomy(string $taxonomy): int
    {
        global $wpdb;

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE taxonomy = %s",
            $taxonomy
        ));

        return (int) $count;
    }

    /**
     * Memeriksa apakah term taxonomy dengan ID tertentu ada
     *
     * @param int $termTaxonomyId ID term taxonomy yang akan diperiksa
     * @return bool True jika term taxonomy ditemukan, false jika tidak
     */
    public static function exists(int $termTaxonomyId): bool
    {
        global $wpdb;

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id = %d",
            $termTaxonomyId
        ));

        return (int) $count > 0;
    }
}
