<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum MenuLocation
 *
 * Represents different menu locations in WordPress theme
 */
enum MenuLocation: string
{
    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case FOOTER = 'footer';
    case MOBILE = 'mobile';
    case SOCIAL = 'social';
    case TOPBAR = 'topbar';
    case SIDEBAR = 'sidebar';

    /**
     * Get human-readable label for menu location
     *
     * @return string Label text
     */
    public function label(): string
    {
        return match ($this) {
            self::PRIMARY => 'Primary Navigation',
            self::SECONDARY => 'Secondary Menu',
            self::FOOTER => 'Footer Menu',
            self::MOBILE => 'Mobile Menu',
            self::SOCIAL => 'Social Links',
            self::TOPBAR => 'Top Bar',
            self::SIDEBAR => 'Sidebar Menu',
        };
    }

    /**
     * Get description for menu location
     *
     * @return string Description text
     */
    public function description(): string
    {
        return match ($this) {
            self::PRIMARY => 'Main website navigation',
            self::FOOTER => 'Appears in website footer',
            self::MOBILE => 'Navigation for mobile devices',
            self::SOCIAL => 'Social media links',
            default => 'Navigation menu',
        };
    }
}
