<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuEntityDto;
use Vigihdev\WpCliModels\DTOs\Entities\Post\PostEntityDto;
use Vigihdev\WpCliModels\Entities\{MenuEntity, MenuItemEntity, PostEntity, TermRelationships};
use Vigihdev\WpCliModels\Enums\{MenuItemType, PostType};
use Vigihdev\WpCliModels\Exceptions\{MenuException, MenuItemException, PostException};


final class MenuItemValidator
{
    private ?PostEntityDto $post = null;
    private ?MenuEntityDto $menu = null;
    private ?TermRelationships $termRelations = null;

    public function __construct(
        private readonly int|string|null $postId = null,
        private readonly int|string|null $menuId = null,
    ) {

        // Memvalidasi apakah post_id ada di database
        if (!$this->post && $postId) {
            $this->post = PostEntity::get($postId);
        }


        if (!$this->menu && $menuId) {
            $this->menu = MenuEntity::get($menuId);
        }

        // Memvalidasi apakah post memiliki relasi dengan menu
        if ($this->post && $this->menu && !$this->termRelations) {
            $this->termRelations = new TermRelationships(
                object_id: $this->post->getId(),
                term_taxonomy_id: $this->menu->getTermTaxonomyId(),
            );
        }
    }

    /**
     * Membuat instance validator baru
     * 
     * @param int|string|null $postId ID post (opsional)
     * @param int|string|null $menuId ID menu (opsional)
     * @return self
     */
    public static function validate(int|string|null $postId = null, int|string|null $menuId = null): self
    {
        return new self($postId, $menuId);
    }

    /**
     * Memvalidasi apakah post_id ada di database
     * 
     * @return self
     * @throws PostException Jika post tidak ditemukan
     */
    public function mustPostExist(): self
    {
        if (!$this->post) {
            throw PostException::notFound((int)$this->postId);
        }
        return $this;
    }

    /**
     * Memvalidasi apakah menu_id ada di database
     * 
     * @return self
     * @throws MenuException Jika menu tidak ditemukan
     */
    public function mustMenuExist(): self
    {
        if (!$this->menu) {
            throw MenuException::notFound((string)$this->menuId);
        }
        return $this;
    }

    /**
     * Memvalidasi apakah title dan link menu item unik di menu tertentu
     * 
     * @param string $menuItemType Tipe menu item (custom, post_type, taxonomy, etc.)
     * @param string $title Title menu item
     * @param string $link Link menu item
     * @return self
     * @throws MenuException Jika menu tidak ditemukan
     * @throws MenuItemException Jika title atau link duplikat
     */
    private function uniqueTitleLink(string $menuItemType, string $title, string $link): self
    {

        $this->mustMenuExist();

        $menu = $this->menu;
        $menuItem = MenuItemEntity::get($menu->getTermId());
        foreach ($menuItem->getIterator() as $item) {

            if ($item->getType() === $menuItemType) {

                if (strtolower($item->getTitle()) === strtolower($title)) {
                    throw MenuItemException::duplicateTitle($title, $menu->getTermId(), [
                        'url' => $item->getUrl(),
                        'title' => $item->getTitle(),
                        'type' => $item->getType(),
                    ]);
                }

                if (strtolower($item->getUrl()) === strtolower($link)) {
                    throw MenuItemException::duplicateLink($link, $menu->getTermId(), [
                        'title' => $item->getTitle(),
                        'type' => $item->getType(),
                    ]);
                }
            }
        }

        return $this;
    }

    /**
     * Memvalidasi apakah title dan link menu item custom unik di menu tertentu
     *
     * @param string $title Title menu item custom
     * @param string $link Link menu item custom
     * @return self mengembalikan instance validator
     * @throws MenuItemException Jika title atau link duplikat
     */
    public function mustBeUniqueCustomItem(string $title, string $link): self
    {
        $this->uniqueTitleLink(MenuItemType::CUSTOM->value, $title, $link);
        return $this;
    }

    /**
     * Memvalidasi apakah menu item dengan ID tertentu ada
     *  dan memiliki relasi term taxonomy yang valid
     * 
     * @return self 
     * @throws MenuItemException Jika menu item tidak ditemukan
     */
    public function mustExist(): self
    {
        $this->mustPostExist();

        if ($this->post->getType() !== PostType::NAV_MENU_ITEM->value) {
            throw PostException::invalidPostType($this->post->getType(), [PostType::NAV_MENU_ITEM->value]);
        }

        return $this;
    }

    public function mustSameAsItemType(string $type): self
    {

        $menuItem = MenuItemEntity::findOne((int)$this->post?->getId(), (int)$this->menu?->getTermId());
        if ($menuItem && $menuItem->getType() !== $type) {
            throw MenuItemException::notSameAsType($type, $menuItem->getType());
        }

        return $this;
    }

    /**
     * Validasi bahwa parent item ada dan valid
     * 
     * @throws MenuItemException Jika parent item tidak ditemukan atau tidak valid
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

        $allowedTypes = array_column(MenuItemType::cases(), 'value');
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
