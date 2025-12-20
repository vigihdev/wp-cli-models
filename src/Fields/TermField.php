<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\FieldInterface;
use Vigihdev\WpCliModels\Fields\BaseField;

final class TermField extends BaseField implements FieldInterface
{

    public function __construct(
        private string $fields,
    ) {}

    public function transform(array $data): array
    {
        $transformed = [];
        foreach ($this->getFieldAttributes() as $key => $value) {
            if (isset($data[$value])) {
                $transformed[$key] = $data[$value];
            }
        }
        return $transformed;
    }

    /**
     * Get the attributes of the fields.
     *
     * @return array<string>
     */
    public function getAttributes(): array
    {
        $fields = preg_replace('/[\s]+/', '', $this->fields);
        return explode(',', $fields);
    }

    /**
     * Get the attributes of the fields.
     *
     * @return array<string, string>
     */
    public function getFieldAttributes(): array
    {
        $attributes = [];
        foreach ($this->getAttributes() as $field) {
            if (isset($this->dataFields()[$field])) {
                $attributes[$field] = $this->dataFields()[$field];
            }
        }
        return $attributes;
    }

    /**
     * Get the attributes of the fields.
     *
     * @return array<string, string>
     */
    private function dataFields(): array
    {
        $attribute = [
            'id' => 'term_id',
            'term_id' => 'term_id',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'description',
            'parent' => 'parent',
            'count' => 'count',
            'taxonomy' => 'taxonomy',
        ];
        return $attribute;
    }
}
