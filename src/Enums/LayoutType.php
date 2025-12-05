<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum LayoutType
 *
 * Represents different layout types for themes
 */
enum LayoutType: string
{
    case BOXED = 'boxed';
    case FULL_WIDTH = 'full-width';
    case FRAMED = 'framed';
    case CONTAINED = 'contained';

    /**
     * Get container CSS class for this layout
     *
     * @return string Container class name
     */
    public function containerClass(): string
    {
        return match ($this) {
            self::BOXED => 'container boxed',
            self::FULL_WIDTH => 'container-fluid',
            self::FRAMED => 'container framed',
            self::CONTAINED => 'container',
        };
    }
}
