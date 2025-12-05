<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

/**
 * Enum CommandStatus
 *
 * Represents status types for CLI commands
 */
enum CommandStatus: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
    case INFO = 'info';

    /**
     * Get color associated with status
     *
     * @return string Color name
     */
    public function color(): string
    {
        return match ($this) {
            self::SUCCESS => 'green',
            self::ERROR => 'red',
            self::WARNING => 'yellow',
            self::INFO => 'blue',
        };
    }

    /**
     * Get icon associated with status
     *
     * @return string Icon character
     */
    public function icon(): string
    {
        return match ($this) {
            self::SUCCESS => '✓',
            self::ERROR => '✗',
            self::WARNING => '⚠',
            self::INFO => 'ℹ',
        };
    }
}
