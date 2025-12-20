<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\FieldInterface;
use Vigihdev\WpCliModels\Fields\BaseField;

final class MediaField extends BaseField implements FieldInterface
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
            'id' => 'ID',
            'title' => 'post_title',
            'filename' => '_wp_attached_file',
            'file' => '_wp_attached_file',
            'url' => 'guid',
            'mime_type' => 'post_mime_type',
            'type' => 'post_mime_type',
            'alt' => '_wp_attachment_image_alt',
            'alt_text' => '_wp_attachment_image_alt',
            'caption' => 'post_excerpt',
            'description' => 'post_content',
            'width' => '_wp_attachment_metadata.width',
            'height' => '_wp_attachment_metadata.height',
            'sizes' => '_wp_attachment_metadata.sizes',
        ];
        return $attribute;
    }
}
