<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Fields;

abstract class BaseMenuItemField
{

    protected function dataItemFields(): array
    {

        return [
            'type' => 'menu-item-type',
            'title' => 'menu-item-title',
            'url' => 'menu-item-url',
            'status' => 'menu-item-status',
            'object-id' => 'menu-item-object-id',
            'object' => 'menu-item-object',
            'xfn' => 'menu-item-xfn',
            'description' => 'menu-item-description',
            'attr-title' => 'menu-item-attr-title',
            'target' => 'menu-item-target',
            'classes' => 'menu-item-classes',
            'position' => 'menu-item-position',
            'parent-id' => 'menu-item-parent-id',
        ];
    }
}
