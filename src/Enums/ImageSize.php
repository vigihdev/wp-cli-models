<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum ImageSize
 *
 * Represents different image sizes in WordPress
 */
enum ImageSize: string
{
    case THUMBNAIL = 'thumbnail';      // 150x150
    case MEDIUM = 'medium';           // 300x300
    case LARGE = 'large';             // 1024x1024
    case FULL = 'full';               // Original

        // Custom sizes
    case BLOG_LIST = 'blog_list';     // 600x400
    case SINGLE_FEATURED = 'single_featured'; // 1200x600
    case GALLERY = 'gallery';         // 800x600
    case AVATAR = 'avatar';           // 100x100

    /**
     * Get dimensions for this image size
     *
     * @return array Dimensions configuration
     */
    public function dimensions(): array
    {
        return match ($this) {
            self::THUMBNAIL => ['width' => 150, 'height' => 150, 'crop' => true],
            self::MEDIUM => ['width' => 300, 'height' => 300, 'crop' => false],
            self::LARGE => ['width' => 1024, 'height' => 1024, 'crop' => false],
            self::BLOG_LIST => ['width' => 600, 'height' => 400, 'crop' => true],
            self::SINGLE_FEATURED => ['width' => 1200, 'height' => 600, 'crop' => true],
            self::GALLERY => ['width' => 800, 'height' => 600, 'crop' => true],
            self::AVATAR => ['width' => 100, 'height' => 100, 'crop' => true],
            default => ['width' => 0, 'height' => 0, 'crop' => false],
        };
    }
}
