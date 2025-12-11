<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Entity;

use Vigihdev\WpCliModels\Exceptions\MenuItemException;
use WP_Post;

final class MenuItemValidator
{
    private ?WP_Post $post;

    public function __construct(
        private readonly int $id
    ) {
        $this->post = get_post($id);
    }

    /**
     * Validasi bahwa item ada
     */
    public function mustExist(): self
    {
        if (!$this->post) {
            throw MenuItemException::notFound($this->id);
        }
        return $this;
    }

    /**
     * Validasi bahwa post type adalah nav_menu_item
     */
    public function mustBeMenuItem(): self
    {
        $this->mustExist(); // Pastikan post exist dulu

        if ($this->post->post_type !== 'nav_menu_item') {
            throw MenuItemException::invalidType(
                $this->post->post_type,
                ['nav_menu_item'],
                $this->id
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa item termasuk dalam menu tertentu
     */
    public function mustBelongToMenu(int $menuId): self
    {
        $this->mustBeMenuItem(); // Pastikan ini menu item

        $menuTerms = wp_get_object_terms($this->id, 'nav_menu');
        $inMenu = false;

        foreach ($menuTerms as $term) {
            if ($term->term_id == $menuId) {
                $inMenu = true;
                break;
            }
        }

        if (!$inMenu) {
            throw new MenuItemException(
                sprintf("Menu item ID %d tidak termasuk dalam menu ID %d", $this->id, $menuId),
                $this->id,
                null,
                null,
                ['menu_id' => $menuId],
                MenuItemException::INVALID_PARENT
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa item adalah parent (bukan child)
     */
    public function mustBeParentItem(): self
    {
        $this->mustBeMenuItem();

        $parentId = (int) get_post_meta($this->id, '_menu_item_menu_item_parent', true);

        if ($parentId !== 0) {
            throw MenuItemException::invalidParent($this->id);
        }
        return $this;
    }

    /**
     * Validasi bahwa item adalah child (bukan parent)
     */
    public function mustBeChildItem(): self
    {
        $this->mustBeMenuItem();

        $parentId = (int) get_post_meta($this->id, '_menu_item_menu_item_parent', true);

        if ($parentId === 0) {
            throw new MenuItemException(
                sprintf("Menu item ID %d adalah parent item, bukan child", $this->id),
                $this->id,
                null,
                null,
                [],
                MenuItemException::INVALID_PARENT
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa item memiliki parent tertentu
     */
    public function mustHaveParent(int $expectedParentId): self
    {
        $this->mustBeChildItem();

        $actualParentId = (int) get_post_meta($this->id, '_menu_item_menu_item_parent', true);

        if ($actualParentId !== $expectedParentId) {
            throw new MenuItemException(
                sprintf(
                    "Menu item ID %d memiliki parent ID %d, bukan %d",
                    $this->id,
                    $actualParentId,
                    $expectedParentId
                ),
                $this->id,
                $actualParentId,
                null,
                ['expected_parent_id' => $expectedParentId],
                MenuItemException::INVALID_PARENT
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa item adalah custom link (type = custom)
     */
    public function mustBeCustomLink(): self
    {
        $this->mustBeMenuItem();

        $type = get_post_meta($this->id, '_menu_item_type', true);

        if ($type !== 'custom') {
            throw new MenuItemException(
                sprintf("Menu item ID %d bukan custom link (type: %s)", $this->id, $type),
                $this->id,
                null,
                null,
                ['item_type' => $type],
                MenuItemException::INVALID_TYPE
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa item memiliki title
     */
    public function mustHaveTitle(): self
    {
        $this->mustBeMenuItem();

        $title = $this->post->post_title;

        if (empty($title)) {
            throw new MenuItemException(
                sprintf("Menu item ID %d tidak memiliki title", $this->id),
                $this->id,
                null,
                null,
                [],
                MenuItemException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa item publish
     */
    public function mustBePublished(): self
    {
        $this->mustBeMenuItem();

        if ($this->post->post_status !== 'publish') {
            throw new MenuItemException(
                sprintf(
                    "Menu item ID %d status bukan 'publish' (status: %s)",
                    $this->id,
                    $this->post->post_status
                ),
                $this->id,
                null,
                null,
                ['post_status' => $this->post->post_status],
                MenuItemException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Get the post object setelah validasi
     */
    public function getPost(): WP_Post
    {
        $this->mustBeMenuItem();
        return $this->post;
    }

    /**
     * Get menu item object setelah validasi
     */
    public function getMenuItem(): object
    {
        $this->mustBeMenuItem();

        // Konversi ke object menu item
        $menuItem = new \stdClass();
        $menuItem->ID = $this->id;
        $menuItem->title = $this->post->post_title;
        $menuItem->url = get_post_meta($this->id, '_menu_item_url', true);
        $menuItem->type = get_post_meta($this->id, '_menu_item_type', true);
        $menuItem->object = get_post_meta($this->id, '_menu_item_object', true);
        $menuItem->object_id = get_post_meta($this->id, '_menu_item_object_id', true);
        $menuItem->parent_id = (int) get_post_meta($this->id, '_menu_item_menu_item_parent', true);
        $menuItem->menu_order = $this->post->menu_order;

        return $menuItem;
    }

    /**
     * Static factory method untuk chain validation
     */
    public static function validate(int $id): self
    {
        return new self($id);
    }
}
