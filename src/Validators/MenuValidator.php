<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Exceptions\MenuException;

final class MenuValidator
{
    public function __construct(
        private readonly int|string $identifier
    ) {}

    public static function validate(int|string $identifier): static
    {
        return new self($identifier);
    }

    /**
     * Validasi bahwa menu dengan identifier tertentu ada
     * 
     * @throws MenuException
     */
    public function mustExist(): self
    {
        $menu = $this->getMenu();

        if (!$menu || is_wp_error($menu)) {
            throw MenuException::notFound((string) $this->identifier);
        }

        return $this;
    }

    /**
     * Validasi bahwa menu location valid
     * 
     * @throws MenuException
     */
    public function mustHaveValidLocation(string $location): self
    {
        $locations = get_registered_nav_menus();

        if (!array_key_exists($location, $locations)) {
            throw MenuException::invalidLocation($location);
        }

        return $this;
    }

    /**
     * Validasi bahwa menu dengan nama tertentu belum ada
     * 
     * @throws MenuException
     */
    public function mustNotExist(string $name): self
    {
        $menu = wp_get_nav_menu_object($name);

        if ($menu && !is_wp_error($menu)) {
            throw MenuException::alreadyExists($name);
        }

        return $this;
    }

    /**
     * Validasi bahwa nama menu unik
     * 
     * @throws MenuException
     */
    public function mustHaveUniqueName(string $name, ?int $excludeMenuId = null): self
    {
        $menu = wp_get_nav_menu_object($name);

        if ($menu && !is_wp_error($menu)) {
            // Jika ada excludeMenuId, cek apakah menu yang ditemukan berbeda
            if ($excludeMenuId && $menu->term_id !== $excludeMenuId) {
                throw MenuException::alreadyExists($name);
            } elseif (!$excludeMenuId) {
                throw MenuException::alreadyExists($name);
            }
        }

        return $this;
    }

    /**
     * Validasi nama menu tidak kosong
     * 
     * @throws MenuException
     */
    public function mustHaveValidName(string $name): self
    {
        $name = trim($name);

        if (empty($name)) {
            throw MenuException::createFailed('', 'Nama menu tidak boleh kosong');
        }

        if (strlen($name) > 200) {
            throw MenuException::createFailed($name, 'Nama menu terlalu panjang (maksimal 200 karakter)');
        }

        return $this;
    }

    /**
     * Validasi untuk create menu
     * 
     * @throws MenuException
     */
    public function validateForCreate(string $name): self
    {
        $this->mustHaveValidName($name);
        $this->mustNotExist($name);

        return $this;
    }

    /**
     * Validasi untuk update menu
     * 
     * @throws MenuException
     */
    public function validateForUpdate(?string $newName = null): self
    {
        $this->mustExist();

        if ($newName !== null) {
            $this->mustHaveValidName($newName);

            // Get current menu ID untuk exclude dari pengecekan duplicate
            $menu = $this->getMenu();
            if ($menu && !is_wp_error($menu)) {
                $this->mustHaveUniqueName($newName, $menu->term_id);
            }
        }

        return $this;
    }

    /**
     * Validasi untuk delete menu
     * 
     * @throws MenuException
     */
    public function validateForDelete(): self
    {
        $this->mustExist();
        return $this;
    }

    /**
     * Validasi untuk assign menu ke location
     * 
     * @throws MenuException
     */
    public function validateForLocationAssignment(string $location): self
    {
        $this->mustExist();
        $this->mustHaveValidLocation($location);

        return $this;
    }

    /**
     * Helper method untuk mendapatkan menu object
     * 
     * @return \WP_Term|false|\WP_Error
     */
    private function getMenu(): \WP_Term|false|\WP_Error
    {
        if (is_numeric($this->identifier)) {
            return wp_get_nav_menu_object((int) $this->identifier);
        }

        return wp_get_nav_menu_object((string) $this->identifier);
    }

    /**
     * Validasi bahwa menu memiliki items
     * 
     * @throws MenuException
     */
    public function mustHaveItems(): self
    {
        $this->mustExist();

        $menu = $this->getMenu();
        if ($menu && !is_wp_error($menu)) {
            $items = wp_get_nav_menu_items($menu->term_id);

            if (empty($items)) {
                throw MenuException::createFailed(
                    $menu->name,
                    'Menu tidak memiliki items'
                );
            }
        }

        return $this;
    }

    /**
     * Validasi bahwa menu tidak sedang digunakan di location manapun
     * 
     * @throws MenuException
     */
    public function mustNotBeAssignedToLocation(): self
    {
        $this->mustExist();

        $menu = $this->getMenu();
        if ($menu && !is_wp_error($menu)) {
            $locations = get_nav_menu_locations();

            foreach ($locations as $location => $menuId) {
                if ($menuId === $menu->term_id) {
                    throw MenuException::deleteFailed(
                        $menu->name,
                        sprintf('Menu sedang digunakan di location: %s', $location)
                    );
                }
            }
        }

        return $this;
    }
}
