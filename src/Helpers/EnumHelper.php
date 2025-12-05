<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Helpers;

use WP_CLI\Utils;

final class EnumHelper
{
    public static function displayTable(string $enumClass): void
    {
        $items = [];

        foreach ($enumClass::cases() as $case) {
            $items[] = [
                'Value' => $case->value,
                'Name' => $case->name,
                'Label' => method_exists($case, 'label') ? $case->label() : '',
            ];
        }

        Utils\format_items('table', $items, ['Value', 'Name', 'Label']);
    }
}
