<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Support;

use WP_CLI\Utils;

final class EnumSupport
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
