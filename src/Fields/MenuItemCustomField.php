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
}
