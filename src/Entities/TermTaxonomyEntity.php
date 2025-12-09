<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\WpCliModels\DTOs\Entities\Terms\TermTaxonomyEntityDto;

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
     * @return TermTaxonomyEntityDto[] Daftar term taxonomies
     */
    public static function findAllByTaxonomy(string $taxonomy, array $args = []): array
    {
        global $wpdb;

        // Valid orderby whitelist
        $allowedOrderby = [
            'tt.term_taxonomy_id',
            'tt.term_id',
            'tt.count',
            'tt.parent',
        ];

        $allowedOrder = ['ASC', 'DESC'];

        $defaults = [
            'hide_empty' => false,
            'orderby'    => 'tt.term_taxonomy_id',
            'order'      => 'ASC',
        ];

        $args = wp_parse_args($args, $defaults);

        // Validate orderby
        if (!in_array($args['orderby'], $allowedOrderby, true)) {
            $args['orderby'] = 'tt.term_taxonomy_id';
        }

        // Validate order
        if (!in_array(strtoupper($args['order']), $allowedOrder, true)) {
            $args['order'] = 'ASC';
        }

        // Build query
        $query = " SELECT 
                    tt.term_taxonomy_id, 
                    tt.term_id, 
                    tt.taxonomy, 
                    tt.description, 
                    tt.parent, 
                    tt.count
                FROM {$wpdb->term_taxonomy} tt
                WHERE tt.taxonomy = %s
            ";

        if (!empty($args['hide_empty'])) {
            $query .= " AND tt.count > 0";
        }

        $query .= " ORDER BY {$args['orderby']} {$args['order']}";

        // Execute
        $results = $wpdb->get_results(
            $wpdb->prepare($query, $taxonomy)
        );

        return array_map(fn($value) => TermTaxonomyEntityDto::fromQuery($value), $results) ?: [];
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
