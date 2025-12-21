<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Generator;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuItemEntityDto;
use Vigihdev\WpCliModels\DTOs\Entities\Post\PostEntityDto;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\TermEntityDto;
use WP_Post;
use WP_Term;

/**
 * Term Relationships Entity 
 * 
 * @property-read int $object_id Object id
 * @property-read int $term_taxonomy_id Term taxonomy id
 * @property-read int $term_order Term order
 * 
 * @method PostEntityDto getPostDto(): PostEntityDto 
 * @method TermEntityDto getTermDto(): TermEntityDto 
 * @method int getObjectId(): int 
 * @method int getTermTaxonomyId(): int 
 * @method int getTermOrder(): int
 * 
 */
final class TermRelationships
{

    public function __construct(
        private readonly int $object_id = 0,
        private readonly int $term_taxonomy_id = 0,
        private readonly int $term_order = 0
    ) {}

    /**
     * Find term relationships by post id
     * 
     * @param int $postId Post id
     * @return Generator<int, TermRelationships> Term relationships
     */
    public static function findByPostId(int $postId): Generator
    {
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT object_id, term_taxonomy_id, term_order
             FROM {$wpdb->term_relationships}
             WHERE object_id = %d",
                $postId
            )
        );

        foreach ($results as $row) {
            yield new self(
                object_id: (int) $row->object_id,
                term_taxonomy_id: (int) $row->term_taxonomy_id,
                term_order: (int) $row->term_order
            );
        }
    }

    /**
     * Find term relationships by term taxonomy id
     * 
     * @param int $term_taxonomy_id Term taxonomy id
     * @return Generator<int, TermRelationships> Term relationships by term taxonomy id
     */
    public static function findByTermTaxonomyId(int $term_taxonomy_id): Generator
    {
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT object_id, term_taxonomy_id, term_order
             FROM {$wpdb->term_relationships}
             WHERE term_taxonomy_id = %d",
                $term_taxonomy_id
            )
        );

        foreach ($results as $row) {
            yield new self(
                object_id: (int) $row->object_id,
                term_taxonomy_id: (int) $row->term_taxonomy_id,
                term_order: (int) $row->term_order
            );
        }
    }


    /**
     * Mengambil post entity dto berdasarkan object id
     *
     * @return PostEntityDto|null
     */
    public function getPostDto(): ?PostEntityDto
    {
        $post = get_post($this->object_id);
        return $post instanceof WP_Post ? PostEntityDto::fromQuery($post) : null;
    }

    /**
     * Mengambil term entity dto berdasarkan term taxonomy id
     *
     * @return TermEntityDto|null
     */
    public function getTermDto(): ?TermEntityDto
    {
        $term = get_term_by('term_taxonomy_id', $this->term_taxonomy_id);
        return $term instanceof WP_Term ? TermEntityDto::fromQuery($term) : null;
    }

    /**
     * Mengambil menu item entity dto berdasarkan object id dan term taxonomy id
     *
     * @return MenuItemEntityDto|null
     */
    public function getMenuItemDto(): ?MenuItemEntityDto
    {
        if (!$this->getPostDto() || !$this->getTermDto()) {
            return null;
        }
        return MenuItemEntity::findOne($this->getPostDto()->getId(), $this->getTermDto()->getTermId());
    }

    /**
     * Mengambil term order
     *
     * @return int
     */
    public function getTermOrder(): int
    {
        return $this->term_order;
    }

    private static function findOne(int $term_taxonomy_id = 0, int $object_id = 0) {}


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
