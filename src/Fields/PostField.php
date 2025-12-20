<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\FieldInterface;
use Vigihdev\WpCliModels\Fields\BaseField;

final class PostField extends BaseField implements FieldInterface
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
            // ID Fields
            'id' => 'ID',
            'post_id' => 'ID',

            // Content Fields
            'title' => 'post_title',
            'post_title' => 'post_title',
            'content' => 'post_content',
            'post_content' => 'post_content',
            'excerpt' => 'post_excerpt',
            'post_excerpt' => 'post_excerpt',

            // Slug & Name
            'slug' => 'post_name',
            'post_name' => 'post_name',
            'name' => 'post_name',

            // Date Fields
            'date' => 'post_date',
            'post_date' => 'post_date',
            'date_gmt' => 'post_date_gmt',
            'post_date_gmt' => 'post_date_gmt',
            'modified' => 'post_modified',
            'post_modified' => 'post_modified',
            'modified_gmt' => 'post_modified_gmt',
            'post_modified_gmt' => 'post_modified_gmt',

            // Status & Type
            'status' => 'post_status',
            'post_status' => 'post_status',
            'type' => 'post_type',
            'post_type' => 'post_type',

            // Author
            'author' => 'post_author',
            'post_author' => 'post_author',
            'author_id' => 'post_author',

            // Parent & Menu Order
            'parent' => 'post_parent',
            'post_parent' => 'post_parent',
            'parent_id' => 'post_parent',
            'menu_order' => 'menu_order',

            // Comment & Ping
            'comment_count' => 'comment_count',
            'comment_status' => 'comment_status',
            'ping_status' => 'ping_status',

            // Password & Visibility
            'password' => 'post_password',
            'post_password' => 'post_password',

            // GUID
            'guid' => 'guid',

            // Meta Fields (common)
            'thumbnail' => '_thumbnail_id',
            'featured_image' => '_thumbnail_id',
        ];
        return $attribute;
    }
}
