<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Support;

/**
 * Field Normalizer for WordPress CLI Commands
 * Handles field aliases and normalization for various WordPress entities
 * 
 */
final class FieldNormalizer
{
    /**
     * Field aliases mapping for different WordPress entities
     * 
     * @var array
     */
    private $aliases = [
        'user' => [
            // ID Fields
            'id' => 'ID',
            'user_id' => 'ID',

            // Login/Username
            'username' => 'user_login',
            'login' => 'user_login',

            // Email
            'email' => 'user_email',

            // Name Fields
            'firstname' => 'first_name',
            'first_name' => 'first_name',
            'lastname' => 'last_name',
            'last_name' => 'last_name',
            'displayname' => 'display_name',
            'display_name' => 'display_name',
            'name' => 'display_name',
            'fullname' => 'display_name',
            'nicename' => 'user_nicename',
            'user_nicename' => 'user_nicename',

            // URL
            'url' => 'user_url',
            'website' => 'user_url',
            'user_url' => 'user_url',

            // Date Fields
            'registered' => 'user_registered',
            'registration_date' => 'user_registered',
            'user_registered' => 'user_registered',

            // Status & Level
            'status' => 'user_status',
            'user_status' => 'user_status',
            'level' => 'user_level',
            'user_level' => 'user_level',

            // Roles & Capabilities
            'role' => 'roles',
            'roles' => 'roles',
            'capabilities' => 'roles',

            // Meta Fields (common)
            'description' => 'description',
            'bio' => 'description',
        ],

        'post' => [
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
        ],

        'comment' => [
            'id' => 'comment_ID',
            'comment_id' => 'comment_ID',
            'post' => 'comment_post_ID',
            'post_id' => 'comment_post_ID',
            'author' => 'comment_author',
            'author_email' => 'comment_author_email',
            'author_url' => 'comment_author_url',
            'author_ip' => 'comment_author_IP',
            'date' => 'comment_date',
            'content' => 'comment_content',
            'karma' => 'comment_karma',
            'approved' => 'comment_approved',
            'agent' => 'comment_agent',
            'type' => 'comment_type',
            'parent' => 'comment_parent',
        ],

        'term' => [
            'id' => 'term_id',
            'term_id' => 'term_id',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'description',
            'parent' => 'parent',
            'count' => 'count',
            'taxonomy' => 'taxonomy',
        ],

        'media' => [
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
        ]
    ];

    /**
     * Default fields for each entity
     * 
     * @var array
     */
    private $defaults = [
        'user' => 'ID,user_login,user_email,roles',
        'post' => 'ID,post_title,post_date,post_status',
        'comment' => 'comment_ID,comment_author,comment_content,comment_date',
        'term' => 'term_id,name,slug,taxonomy',
        'media' => 'ID,post_title,guid,post_mime_type',
    ];

    /**
     * Normalize fields string for specific entity
     * 
     * @param string $entity Entity type (user, post, comment, term, media)
     * @param string $fields Comma-separated fields
     * @return string Normalized comma-separated fields
     */
    public function normalize(string $entity, string $fields): string
    {
        // If fields is empty, use defaults
        if (empty(trim($fields))) {
            return $this->defaults[$entity] ?? $fields;
        }

        // Check if entity has aliases
        if (!isset($this->aliases[$entity])) {
            return $fields;
        }

        $normalized = [];
        $entityAliases = $this->aliases[$entity];

        foreach (explode(',', $fields) as $field) {
            $field = trim($field);

            if (empty($field)) {
                continue;
            }

            // Handle field with dot notation (e.g., metadata.width)
            if (strpos($field, '.') !== false) {
                $normalized[] = $field;
                continue;
            }

            // Check for alias
            if (isset($entityAliases[$field])) {
                $normalized[] = $entityAliases[$field];
            } else {
                // If field not in aliases, check if it's already a valid field
                $normalized[] = $field;
            }
        }

        return implode(',', array_unique($normalized));
    }

    /**
     * Normalize single field
     * 
     * @param string $entity Entity type
     * @param string $field Single field name
     * @return string Normalized field name
     */
    public function normalizeSingle(string $entity, string $field): string
    {
        if (!isset($this->aliases[$entity])) {
            return $field;
        }

        $field = trim($field);

        return $this->aliases[$entity][$field] ?? $field;
    }

    /**
     * Get available fields for entity
     * 
     * @param string $entity Entity type
     * @param bool $includeAliases Include alias names
     * @return array Available fields
     */
    public function getAvailableFields(string $entity, bool $includeAliases = true): array
    {
        if (!isset($this->aliases[$entity])) {
            return [];
        }

        $fields = array_values($this->aliases[$entity]);

        if ($includeAliases) {
            $aliases = array_keys($this->aliases[$entity]);
            $fields = array_unique(array_merge($fields, $aliases));
        }

        sort($fields);
        return $fields;
    }

    /**
     * Get default fields for entity
     * 
     * @param string $entity Entity type
     * @return string Default fields
     */
    public function getDefaultFields(string $entity): string
    {
        return $this->defaults[$entity] ?? '';
    }

    /**
     * Validate if field is valid for entity
     * 
     * @param string $entity Entity type
     * @param string $field Field name
     * @return bool True if valid
     */
    public function isValidField(string $entity, string $field): bool
    {
        if (!isset($this->aliases[$entity])) {
            return false;
        }

        $field = trim($field);
        $availableFields = $this->getAvailableFields($entity, true);

        return in_array($field, $availableFields, true);
    }

    /**
     * Parse fields string into array
     * 
     * @param string $fields Comma-separated fields
     * @return array Fields array
     */
    public function parseFields(string $fields): array
    {
        $result = [];
        foreach (explode(',', $fields) as $field) {
            $field = trim($field);
            if (!empty($field)) {
                $result[] = $field;
            }
        }
        return $result;
    }

    /**
     * Convert fields array to string
     * 
     * @param array $fields Fields array
     * @return string Comma-separated fields
     */
    public function stringifyFields(array $fields): string
    {
        return implode(',', array_filter($fields));
    }

    /**
     * Get field label/description for display
     * 
     * @param string $entity Entity type
     * @param string $field Field name
     * @return string Field label
     */
    public function getFieldLabel(string $entity, string $field): string
    {
        if (!isset($this->aliases[$entity])) {
            return $field;
        }

        $labels = [
            'ID' => 'ID',
            'user_login' => 'Username',
            'user_email' => 'Email',
            'roles' => 'Roles',
            'post_title' => 'Title',
            'post_date' => 'Date',
            'post_status' => 'Status',
            'comment_author' => 'Author',
            'comment_content' => 'Content',
            'comment_date' => 'Date',
        ];

        $normalizedField = $this->normalizeSingle($entity, $field);
        return $labels[$normalizedField] ?? $normalizedField;
    }

    /**
     * Add custom aliases for entity
     * 
     * @param string $entity Entity type
     * @param array $aliases New aliases [alias => real_field]
     */
    public function addAliases(string $entity, array $aliases): void
    {
        if (!isset($this->aliases[$entity])) {
            $this->aliases[$entity] = [];
        }

        $this->aliases[$entity] = array_merge($this->aliases[$entity], $aliases);
    }

    /**
     * Remove aliases for entity
     * 
     * @param string $entity Entity type
     * @param array $aliases Aliases to remove
     */
    public function removeAliases(string $entity, array $aliases): void
    {
        if (!isset($this->aliases[$entity])) {
            return;
        }

        foreach ($aliases as $alias) {
            unset($this->aliases[$entity][$alias]);
        }
    }

    /**
     * Get all entities with aliases
     * 
     * @return array Entity names
     */
    public function getAvailableEntities(): array
    {
        return array_keys($this->aliases);
    }

    /**
     * Check if entity is supported
     * 
     * @param string $entity Entity type
     * @return bool True if supported
     */
    public function isEntitySupported(string $entity): bool
    {
        return isset($this->aliases[$entity]);
    }

    /**
     * Bulk normalize fields for multiple entities
     * 
     * @param array $entitiesFields ['entity' => 'fields_string']
     * @return array Normalized fields
     */
    public function bulkNormalize(array $entitiesFields): array
    {
        $result = [];
        foreach ($entitiesFields as $entity => $fields) {
            $result[$entity] = $this->normalize($entity, $fields);
        }
        return $result;
    }
}
