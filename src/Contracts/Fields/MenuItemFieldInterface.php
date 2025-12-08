<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Fields;

/**
 * Interface MenuItemFieldInterface
 *
 * Interface untuk mendefinisikan struktur data item menu dasar di WP-CLI
 */
interface MenuItemFieldInterface
{
    /**
     * Mendapatkan tipe dari menu item
     *
     * @return string Tipe menu item
     */
    public function getType(): string;

    /**
     * Mendapatkan label dari menu item
     *
     * @return string|null Label untuk menu item
     */
    public function getLabel(): ?string;

    /**
     * Mendapatkan title dari menu item
     *
     * @return string|null Title untuk menu item
     */
    public function getTitle(): ?string;

    /**
     * Mendapatkan URL dari menu item
     *
     * @return string|null URL untuk menu item
     */
    public function getUrl(): ?string;
}
