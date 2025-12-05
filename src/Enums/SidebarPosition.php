<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum SidebarPosition
 *
 * Represents different sidebar positions in theme layouts
 */
enum SidebarPosition: string
{
    case LEFT = 'left';
    case RIGHT = 'right';
    case TOP = 'top';
    case BOTTOM = 'bottom';
    case NONE = 'none';

    /**
     * Check if position is vertical (left or right)
     *
     * @return bool True if vertical position
     */
    public function isVertical(): bool
    {
        return in_array($this, [self::LEFT, self::RIGHT]);
    }

    /**
     * Check if position is horizontal (top or bottom)
     *
     * @return bool True if horizontal position
     */
    public function isHorizontal(): bool
    {
        return in_array($this, [self::TOP, self::BOTTOM]);
    }
}
