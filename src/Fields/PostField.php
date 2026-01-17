<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\FieldInterface;

final class PostField
{


    private function transform(array $data): array
    {
        $transformed = [];
        foreach ($this->dataFields() as $key => $value) {
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
    private function getAttributes(): array
    {
        $fields = preg_replace('/[\s]+/', '', '');
        return explode(',', $fields);
    }

    /**
     * Get the attributes of the fields.
     *
     * @return array<string, string>
     */
    private function getFieldAttributes(): array
    {
        $attributes = [];
        foreach ($this->getAttributes() as $field) {
            if (isset($this->dataFields()[$field])) {
                $attributes[$field] = $this->dataFields()[$field];
            }
        }
        return $attributes;
    }

    public function dtoTransform(array $data): array
    {
        $transformed = [];
        foreach ($this->dtoDataFields() as $key => $value) {
            if (isset($data[$key])) {
                $transformed[$value] = $data[$key];
            }
        }
        return $transformed;
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

    private function dtoDataFields(): array
    {

        $attributes = [
            'post_title'            => 'title',
            'post_name'             => 'name',
            'post_status'           => 'status',
            'post_author'           => 'author',
            'post_type'             => 'type',
            'post_date'             => 'date',
            'post_date_gmt'         => 'dateGmt',
            'post_modified'         => 'modified',
            'post_modified_gmt'     => 'modifiedGmt',
            'post_content'          => 'content',
            'post_content_filtered' => 'contentFiltered',
            'post_excerpt'          => 'excerpt',
            'comment_status'        => 'commentStatus',
            'ping_status'           => 'pingStatus',
            'post_password'         => 'password',
            'to_ping'               => 'toPing',
            'pinged'                => 'pinged',
            'post_parent'           => 'parent',
            'menu_order'            => 'menuOrder',
            'post_mime_type'        => 'mimeType',
            'guid'                  => 'guid',
            'post_category'         => 'category',
            'tags_input'            => 'tagsInput',
            'tax_input'             => 'taxInput',
            'meta_input'            => 'metaInput',
        ];

        return $attributes;
    }
}
