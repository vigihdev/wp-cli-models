<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

final class TermRelationships
{
    /**
     * Mengambil semua term relationships untuk object tertentu
     *
     * @param int $objectId ID object (post, link, dll)
     * @param string $objectType Tipe object ('post', 'link', dll)
     * @return array Daftar term relationships
     */
    public static function findByObjectId(int $objectId, string $objectType = 'post'): array
    {
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT * FROM {$wpdb->term_relationships} tr 
             LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
             WHERE tr.object_id = %d",
            $objectId
        );

        if ($objectType !== '') {
            $query .= $wpdb->prepare(" AND tt.taxonomy IN (" .
                implode(',', array_fill(0, count(get_object_taxonomies($objectType)), '%s')) .
                ")", get_object_taxonomies($objectType));
        }

        return $wpdb->get_results($query);
    }

    /**
     * Mengambil semua object IDs yang terkait dengan term taxonomy tertentu
     *
     * @param int $termTaxonomyId ID term taxonomy
     * @return array Daftar object IDs
     */
    public static function findObjectIdsByTermTaxonomyId(int $termTaxonomyId): array
    {
        global $wpdb;

        $objectIds = $wpdb->get_col($wpdb->prepare(
            "SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d",
            $termTaxonomyId
        ));

        return $objectIds ?: [];
    }

    /**
     * Menambahkan relationship antara object dan term taxonomy
     *
     * @param int $objectId ID object
     * @param int $termTaxonomyId ID term taxonomy
     * @param int $termOrder Urutan term (default: 0)
     * @return bool True jika berhasil, false jika gagal
     */
    public static function add(int $objectId, int $termTaxonomyId, int $termOrder = 0): bool
    {
        global $wpdb;

        $result = $wpdb->insert(
            $wpdb->term_relationships,
            [
                'object_id' => $objectId,
                'term_taxonomy_id' => $termTaxonomyId,
                'term_order' => $termOrder
            ],
            ['%d', '%d', '%d']
        );

        // Update count pada term_taxonomy
        if ($result !== false) {
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->term_taxonomy} SET count = count + 1 WHERE term_taxonomy_id = %d",
                $termTaxonomyId
            ));

            return true;
        }

        return false;
    }

    /**
     * Menghapus relationship antara object dan term taxonomy
     *
     * @param int $objectId ID object
     * @param int $termTaxonomyId ID term taxonomy
     * @return bool True jika berhasil, false jika gagal
     */
    public static function remove(int $objectId, int $termTaxonomyId): bool
    {
        global $wpdb;

        $result = $wpdb->delete(
            $wpdb->term_relationships,
            [
                'object_id' => $objectId,
                'term_taxonomy_id' => $termTaxonomyId
            ],
            ['%d', '%d']
        );

        // Update count pada term_taxonomy
        if ($result !== false) {
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->term_taxonomy} SET count = GREATEST(0, count - 1) WHERE term_taxonomy_id = %d",
                $termTaxonomyId
            ));

            return true;
        }

        return false;
    }

    /**
     * Menghapus semua relationships untuk object tertentu
     *
     * @param int $objectId ID object
     * @return bool True jika berhasil, false jika gagal
     */
    public static function removeAllForObject(int $objectId): bool
    {
        global $wpdb;

        // Dapatkan semua term_taxonomy_id yang terkait dengan object ini
        $termTaxonomyIds = $wpdb->get_col($wpdb->prepare(
            "SELECT term_taxonomy_id FROM {$wpdb->term_relationships} WHERE object_id = %d",
            $objectId
        ));

        // Hapus semua relationships
        $result = $wpdb->delete(
            $wpdb->term_relationships,
            ['object_id' => $objectId],
            ['%d']
        );

        // Update count pada semua term_taxonomy yang terkait
        if ($result !== false && !empty($termTaxonomyIds)) {
            foreach ($termTaxonomyIds as $termTaxonomyId) {
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->term_taxonomy} SET count = GREATEST(0, count - 1) WHERE term_taxonomy_id = %d",
                    $termTaxonomyId
                ));
            }

            return true;
        }

        return $result !== false;
    }

    /**
     * Memeriksa apakah object memiliki relationship dengan term taxonomy tertentu
     *
     * @param int $objectId ID object
     * @param int $termTaxonomyId ID term taxonomy
     * @return bool True jika relationship ditemukan, false jika tidak
     */
    public static function exists(int $objectId, int $termTaxonomyId): bool
    {
        global $wpdb;

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->term_relationships} WHERE object_id = %d AND term_taxonomy_id = %d",
            $objectId,
            $termTaxonomyId
        ));

        return (int) $count > 0;
    }
}
