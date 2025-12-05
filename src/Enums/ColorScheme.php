<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum ColorScheme
 *
 * Represents different color schemes for themes
 */
enum ColorScheme: string
{
    case LIGHT = 'light';
    case DARK = 'dark';
    case AUTO = 'auto';
    case CONTRAST = 'contrast';

    /**
     * Check if scheme is dark variant
     *
     * @return bool True if dark scheme
     */
    public function isDark(): bool
    {
        return $this === self::DARK;
    }

    /**
     * Get CSS class for this color scheme
     *
     * @return string CSS class name
     */
    public function cssClass(): string
    {
        return 'theme-' . $this->value;
    }
}
