<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum CapabilityType
 *
 * Represents different capability types in WordPress
 */
enum CapabilityType: string
{
    case POST = 'post';
    case PAGE = 'page';
    case PRODUCT = 'product';
    case PORTFOLIO = 'portfolio';

    /**
     * Get capabilities associated with this type
     *
     * @return array List of capabilities
     */
    public function getCapabilities(): array
    {
        return match ($this) {
            self::POST, self::PAGE => [
                'edit_' . $this->value,
                'read_' . $this->value,
                'delete_' . $this->value,
                'edit_' . $this->value . 's',
                'edit_others_' . $this->value . 's',
                'publish_' . $this->value . 's',
                'read_private_' . $this->value . 's',
            ],
            default => [
                'edit_' . $this->value,
                'read_' . $this->value,
                'delete_' . $this->value,
                'edit_' . $this->value . 's',
            ],
        };
    }
}
