<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Entities\TermRelationships;
use Vigihdev\WpCliModels\Enums\PostType;
use Vigihdev\WpCliModels\Exceptions\MenuItemException;
use Vigihdev\WpCliModels\Exceptions\PostException;

final class MenuItemValidator
{
    public function __construct(
        private readonly int $postId,
    ) {}

    public static function validate(int $postId): static
    {
        return new self($postId);
    }

    /**
     * Memvalidasi apakah menu item dengan ID tertentu ada
     *  dan memiliki relasi term taxonomy yang valid
     * 
     * @return self 
     * @throws MenuItemException
     */
    public function mustExist(): self
    {
        $postId = $this->postId;

        // Cek apakah menu item ada di database
        $post = get_post($postId);
        if (!$post) {
            throw PostException::notFound($postId);
        }

        // Cek apakah menu item memiliki relasi term taxonomy yang valid
        foreach (TermRelationships::findByPostId($postId) as $relation) {
            $postDto = $relation->getPostDto();
            if ($postDto->getType() !== PostType::NAV_MENU_ITEM->value) {
                throw PostException::invalidPostType($postDto->getType(), [PostType::NAV_MENU_ITEM->value]);
            }
            $term = $relation->getTermDto();
        }

        return $this;
    }

    /**
     * Validasi bahwa parent item ada dan valid
     * 
     * @throws MenuItemException
     */
    public function mustHaveValidParent(int $parentId, int $menuId): self
    {
        if ($parentId <= 0) {
            return $this; // Parent 0 = top-level item, valid
        }

        $parentItem = wp_setup_nav_menu_item(get_post($parentId));

        if (!$parentItem || $parentItem->post_type !== 'nav_menu_item') {
            throw MenuItemException::parentNotFound($parentId, $menuId);
        }

        // Cek apakah parent item ada di menu yang sama
        $parentMenus = wp_get_object_terms($parentId, 'nav_menu', ['fields' => 'ids']);
        if (!in_array($menuId, $parentMenus)) {
            throw MenuItemException::invalidParent($parentId);
        }

        return $this;
    }

    /**
     * Validasi bahwa tidak ada circular reference dalam hierarchy
     * 
     * @throws MenuItemException
     */
    public function mustNotCreateCircularReference(int $itemId, int $parentId): self
    {
        if ($itemId === $parentId) {
            throw MenuItemException::invalidParent($itemId);
        }

        // Cek apakah parentId adalah descendant dari itemId
        $currentParent = $parentId;
        $maxDepth = 10; // Prevent infinite loop
        $depth = 0;

        while ($currentParent > 0 && $depth < $maxDepth) {
            if ($currentParent === $itemId) {
                throw MenuItemException::invalidParent($itemId);
            }

            $parentItem = wp_setup_nav_menu_item(get_post($currentParent));
            $currentParent = $parentItem ? (int) $parentItem->menu_item_parent : 0;
            $depth++;
        }

        return $this;
    }

    /**
     * Validasi bahwa title tidak duplikat di parent yang sama
     * 
     * @throws MenuItemException
     */
    public function mustHaveUniqueTitle(string $title, int $parentId, int $menuId, ?int $excludeItemId = null): self
    {
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT p.ID 
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE p.post_type = 'nav_menu_item'
            AND p.post_title = %s
            AND pm.meta_key = '_menu_item_menu_item_parent'
            AND pm.meta_value = %d
            AND tt.term_id = %d
            AND tt.taxonomy = 'nav_menu'",
            $title,
            $parentId,
            $menuId
        );

        if ($excludeItemId) {
            $query .= $wpdb->prepare(" AND p.ID != %d", $excludeItemId);
        }

        $existingItem = $wpdb->get_var($query);

        if ($existingItem) {
            throw MenuItemException::duplicateTitle($title, $parentId);
        }

        return $this;
    }

    /**
     * Validasi tipe menu item
     * 
     * @throws MenuItemException
     */
    public function mustBeValidType(string $type): self
    {
        $allowedTypes = ['post_type', 'taxonomy', 'custom'];

        if (!in_array($type, $allowedTypes, true)) {
            throw MenuItemException::invalidType($type, $allowedTypes);
        }

        return $this;
    }

    /**
     * Validasi posisi menu item
     * 
     * @throws MenuItemException
     */
    public function mustHaveValidPosition(int $position): self
    {
        if ($position < 0) {
            throw MenuItemException::invalidPosition($position);
        }

        return $this;
    }

    /**
     * Validasi untuk create menu item
     * 
     * @throws MenuItemException
     */
    public function validateForCreate(
        string $title,
        string $type,
        int $parentId,
        int $menuId,
        int $position = 0
    ): self {
        $this->mustBeValidType($type);
        $this->mustHaveValidParent($parentId, $menuId);
        $this->mustHaveUniqueTitle($title, $parentId, $menuId);
        $this->mustHaveValidPosition($position);

        return $this;
    }

    /**
     * Validasi untuk update menu item
     * 
     * @throws MenuItemException
     */
    public function validateForUpdate(
        int $itemId,
        ?string $title = null,
        ?int $parentId = null,
        ?int $menuId = null,
        ?int $position = null
    ): self {
        $this->mustExist();

        if ($parentId !== null && $menuId !== null) {
            $this->mustHaveValidParent($parentId, $menuId);
            $this->mustNotCreateCircularReference($itemId, $parentId);
        }

        if ($title !== null && $parentId !== null && $menuId !== null) {
            $this->mustHaveUniqueTitle($title, $parentId, $menuId, $itemId);
        }

        if ($position !== null) {
            $this->mustHaveValidPosition($position);
        }

        return $this;
    }

    /**
     * Validasi untuk delete menu item
     * 
     * @throws MenuItemException
     */
    public function validateForDelete(): self
    {
        $this->mustExist();
        return $this;
    }
}
