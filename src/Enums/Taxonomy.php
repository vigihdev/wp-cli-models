<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum Taxonomy
 *
 * Represents different taxonomies in WordPress
 */
enum Taxonomy: string
{
    case CATEGORY = 'category';
    case TAG = 'post_tag';
    case NAV_MENU = 'nav_menu';
    case LINK_CATEGORY = 'link_category';
    case POST_FORMAT = 'post_format';

        // Custom taxonomies
    case PRODUCT_CAT = 'product_cat';
    case PRODUCT_TAG = 'product_tag';
    case PORTFOLIO_CAT = 'portfolio_category';

    /**
     * Check if taxonomy is appropriate for given post type
     *
     * @param PostType $postType Post type to check against
     * @return bool True if taxonomy applies to post type
     */
    public function isForPostType(PostType $postType): bool
    {
        return match ($this) {
            self::CATEGORY, self::TAG => $postType === PostType::POST,
            self::PRODUCT_CAT, self::PRODUCT_TAG => $postType === PostType::PRODUCT,
            self::PORTFOLIO_CAT => $postType === PostType::PORTFOLIO,
            default => false,
        };
    }
}
