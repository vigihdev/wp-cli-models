<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Entity;

use Vigihdev\WpCliModels\Exceptions\MenuException;
use Vigihdev\WpCliModels\Exceptions\MenuItemException;
use WP_Term;

final class MenuValidator
{
    private ?WP_Term $menu;
    private ?int $menuId;
    private ?string $menuIdentifier;

    public function __construct(string|int $identifier)
    {
        $this->menuIdentifier = is_numeric($identifier) ? null : (string) $identifier;
        $this->menuId = is_numeric($identifier) ? (int) $identifier : null;
        $this->menu = $this->resolveMenu($identifier);
    }

    /**
     * Resolve menu dari identifier
     */
    private function resolveMenu(string|int $identifier): ?WP_Term
    {
        // Jika numeric, cari sebagai ID
        if (is_numeric($identifier)) {
            $menu = wp_get_nav_menu_object((int) $identifier);
            if ($menu) {
                return $menu;
            }
        }

        // Coba sebagai menu name
        $menu = wp_get_nav_menu_object($identifier);
        if ($menu) {
            return $menu;
        }

        // Coba sebagai theme location
        $locations = get_nav_menu_locations();
        if (isset($locations[$identifier])) {
            $menu = wp_get_nav_menu_object($locations[$identifier]);
            if ($menu) {
                return $menu;
            }
        }

        return null;
    }

    /**
     * Validasi bahwa menu harus exist
     */
    public function mustExist(): self
    {
        if (!$this->menu) {
            throw MenuException::notFound(
                $this->menuIdentifier ?? (string) $this->menuId
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa menu ada dan return ID
     */
    public function mustExistAndGetId(): int
    {
        $this->mustExist();
        return $this->menu->term_id;
    }

    /**
     * Validasi bahwa menu ada di theme location tertentu
     */
    public function mustBeInLocation(string $location): self
    {
        $this->mustExist();

        $locations = get_nav_menu_locations();

        if (!isset($locations[$location]) || $locations[$location] != $this->menu->term_id) {
            throw MenuException::invalidLocation($location);
        }
        return $this;
    }

    /**
     * Validasi bahwa menu memiliki setidaknya X items
     */
    public function mustHaveMinimumItems(int $minItems): self
    {
        $this->mustExist();

        $items = wp_get_nav_menu_items($this->menu->term_id);
        $count = $items ? count($items) : 0;

        if ($count < $minItems) {
            throw new MenuException(
                sprintf("Menu harus memiliki minimal %d items (saat ini: %d)", $minItems, $count),
                $this->menu->name,
                $this->menu->term_id,
                [
                    'current_count' => $count,
                    'required_count' => $minItems
                ],
                MenuException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa menu tidak memiliki lebih dari X items
     */
    public function mustHaveMaximumItems(int $maxItems): self
    {
        $this->mustExist();

        $items = wp_get_nav_menu_items($this->menu->term_id);
        $count = $items ? count($items) : 0;

        if ($count > $maxItems) {
            throw new MenuException(
                sprintf("Menu tidak boleh memiliki lebih dari %d items (saat ini: %d)", $maxItems, $count),
                $this->menu->name,
                $this->menu->term_id,
                [
                    'current_count' => $count,
                    'max_allowed' => $maxItems
                ],
                MenuException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa menu belum memiliki item dengan title tertentu
     */
    public function mustNotHaveItemWithTitle(string $title, ?int $excludeId = null): self
    {
        $this->mustExist();

        $items = wp_get_nav_menu_items($this->menu->term_id);

        if ($items) {
            foreach ($items as $item) {
                // Skip item dengan ID yang dikecualikan
                if ($excludeId && $item->ID == $excludeId) {
                    continue;
                }

                if (strtolower($item->title) === strtolower($title)) {
                    throw MenuItemException::duplicateTitle($title, 0);
                }
            }
        }
        return $this;
    }

    /**
     * Validasi bahwa menu belum memiliki child dengan title tertentu di parent tertentu
     */
    public function mustNotHaveChildWithTitle(string $title, int $parentId): self
    {
        $this->mustExist();

        $items = wp_get_nav_menu_items($this->menu->term_id);

        if ($items) {
            foreach ($items as $item) {
                if (
                    $item->menu_item_parent == $parentId &&
                    strtolower($item->title) === strtolower($title)
                ) {
                    throw MenuItemException::duplicateTitle($title, $parentId);
                }
            }
        }
        return $this;
    }

    /**
     * Validasi bahwa menu memiliki parent item dengan ID tertentu
     */
    public function mustHaveParentItem(int $parentId): self
    {
        $this->mustExist();

        $items = wp_get_nav_menu_items($this->menu->term_id);
        $parentFound = false;

        if ($items) {
            foreach ($items as $item) {
                if ($item->ID == $parentId && $item->menu_item_parent == 0) {
                    $parentFound = true;
                    break;
                }
            }
        }

        if (!$parentFound) {
            throw MenuItemException::parentNotFound($parentId, $this->menu->term_id);
        }
        return $this;
    }

    /**
     * Validasi bahwa menu memiliki kapasitas untuk menambahkan X items
     */
    public function mustHaveCapacity(int $additionalItems): self
    {
        $this->mustExist();

        $items = wp_get_nav_menu_items($this->menu->term_id);
        $currentCount = $items ? count($items) : 0;
        $maxCapacity = apply_filters('menu_capacity_limit', 100, $this->menu->term_id);

        if (($currentCount + $additionalItems) > $maxCapacity) {
            throw new MenuException(
                sprintf(
                    "Menu tidak memiliki kapasitas untuk %d items tambahan. " .
                        "Kapasitas: %d/%d (current/max)",
                    $additionalItems,
                    $currentCount,
                    $maxCapacity
                ),
                $this->menu->name,
                $this->menu->term_id,
                [
                    'current_items' => $currentCount,
                    'additional_items' => $additionalItems,
                    'max_capacity' => $maxCapacity,
                    'will_be' => $currentCount + $additionalItems
                ],
                MenuException::CREATE_FAILED
            );
        }
        return $this;
    }

    /**
     * Validasi bahwa menu adalah primary menu
     */
    public function mustBePrimaryMenu(): self
    {
        $this->mustExist();

        $primaryLocation = 'primary';
        $locations = get_nav_menu_locations();

        $isPrimary = isset($locations[$primaryLocation]) &&
            $locations[$primaryLocation] == $this->menu->term_id;

        if (!$isPrimary) {
            throw new MenuException(
                sprintf("Menu '%s' bukan primary menu", $this->menu->name),
                $this->menu->name,
                $this->menu->term_id,
                ['expected_location' => $primaryLocation],
                MenuException::INVALID_LOCATION
            );
        }
        return $this;
    }

    /**
     * Get menu object setelah validasi
     */
    public function getMenu(): WP_Term
    {
        $this->mustExist();
        return $this->menu;
    }

    /**
     * Get menu ID setelah validasi
     */
    public function getId(): int
    {
        $this->mustExist();
        return $this->menu->term_id;
    }

    /**
     * Get menu name setelah validasi
     */
    public function getName(): string
    {
        $this->mustExist();
        return $this->menu->name;
    }

    /**
     * Get all menu items setelah validasi
     */
    public function getItems(): array
    {
        $this->mustExist();
        $items = wp_get_nav_menu_items($this->menu->term_id);
        return $items ?: [];
    }

    /**
     * Get parent items saja
     */
    public function getParentItems(): array
    {
        $items = $this->getItems();
        return array_filter($items, fn($item) => $item->menu_item_parent == 0);
    }

    /**
     * Get child items untuk parent tertentu
     */
    public function getChildItems(int $parentId): array
    {
        $items = $this->getItems();
        return array_filter($items, fn($item) => $item->menu_item_parent == $parentId);
    }

    /**
     * Static factory method
     */
    public static function validate(string|int $identifier): self
    {
        return new self($identifier);
    }

    /**
     * Helper: Cek apakah menu exists
     */
    public static function exists(string|int $identifier): bool
    {
        try {
            (new self($identifier))->mustExist();
            return true;
        } catch (MenuException $e) {
            return false;
        }
    }

    /**
     * Helper: Dapatkan menu ID dari identifier
     */
    public static function getIdFromIdentifier(string|int $identifier): ?int
    {
        try {
            return (new self($identifier))->mustExistAndGetId();
        } catch (MenuException $e) {
            return null;
        }
    }

    /**
     * Helper: List semua menu yang tersedia
     */
    public static function listAvailableMenus(): array
    {
        $menus = wp_get_nav_menus();
        $result = [];

        foreach ($menus as $menu) {
            $items = wp_get_nav_menu_items($menu->term_id);
            $itemCount = $items ? count($items) : 0;

            // Cek theme locations
            $locations = [];
            $allLocations = get_nav_menu_locations();
            foreach ($allLocations as $location => $menuId) {
                if ($menuId == $menu->term_id) {
                    $locations[] = $location;
                }
            }

            $result[] = [
                'id' => $menu->term_id,
                'name' => $menu->name,
                'slug' => $menu->slug,
                'count' => $itemCount,
                'locations' => $locations,
                'description' => $menu->description
            ];
        }

        return $result;
    }
}
