<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

enum PostType: string
{
    case POST = 'post';
    case PAGE = 'page';
    case ATTACHMENT = 'attachment';
    case REVISION = 'revision';
    case NAV_MENU_ITEM = 'nav_menu_item';
    case PRODUCT = 'product';
    case PORTFOLIO = "portfolio";
    case TESTIMONIAL = 'testimonial';
    case TEAM = 'team';

    public function label(): string
    {
        return match ($this) {
            self::POST => 'Post',
            self::PAGE => 'Page',
            self::PRODUCT => 'Product',
            self::PORTFOLIO => 'Portfolio',
            self::TESTIMONIAL => 'Testimonial',
            self::TEAM => 'Team Member',
            default => ucfirst($this->value)
        };
    }

    public function isBuiltIn(): bool
    {
        return in_array($this, [
            self::POST,
            self::PAGE,
            self::ATTACHMENT,
            self::REVISION,
            self::NAV_MENU_ITEM,
        ]);
    }
}
