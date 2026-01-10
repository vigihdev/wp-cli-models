<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Fields;

final class MenuItemCustomField extends BaseMenuItemField
{

    public function transform(array $data): array
    {
        $transformed = [];
        foreach ($this->dataItemFields() as $key => $value) {
            if (isset($data[$key])) {
                $transformed[$value] = $data[$key];
            }
        }
        return $transformed;
    }

    public function dtotransform(array $data): array
    {
        $transformed = [];
        foreach ($this->dtoItemFields() as $key => $value) {
            if (isset($data[$key])) {
                $transformed[$value] = $data[$key];
            }
        }
        return $transformed;
    }

    private function dataItemFields(): array
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

    private function dtoItemFields(): array
    {
        return [
            'menu' => 'menu',
            'type' => 'type',
            'title' => 'title',
            'link' => 'link',
            'url' => 'link',
            'status' => 'status',
            'object-id' => 'objectId',
            'object' => 'object',
            'xfn' => 'xfn',
            'description' => 'description',
            'attr-title' => 'attrTitle',
            'target' => 'target',
            'classes' => 'classes',
            'position' => 'position',
            'parent-id' => 'parent_id',
            'parent_id' => 'parentId',
        ];
    }
}
