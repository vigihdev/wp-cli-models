<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum UserRole
 *
 * Represents different user roles in WordPress
 */
enum UserRole: string
{
    case ADMINISTRATOR = 'administrator';
    case EDITOR = 'editor';
    case AUTHOR = 'author';
    case CONTRIBUTOR = 'contributor';
    case SUBSCRIBER = 'subscriber';

        // Custom roles
    case SHOP_MANAGER = 'shop_manager';
    case CUSTOMER = 'customer';

    /**
     * Get display name for user role
     *
     * @return string Display name
     */
    public function displayName(): string
    {
        return match ($this) {
            self::ADMINISTRATOR => 'Administrator',
            self::EDITOR => 'Editor',
            self::AUTHOR => 'Author',
            self::CONTRIBUTOR => 'Contributor',
            self::SUBSCRIBER => 'Subscriber',
            self::SHOP_MANAGER => 'Shop Manager',
            self::CUSTOMER => 'Customer',
        };
    }

    /**
     * Get capabilities for this user role
     *
     * @return array List of capabilities
     */
    public function capabilities(): array
    {
        // Return default WordPress capabilities for each role
        return get_role($this->value)->capabilities ?? [];
    }
}
