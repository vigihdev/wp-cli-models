<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\FieldInterface;
use Vigihdev\WpCliModels\Fields\BaseField;

final class UserField extends BaseField implements FieldInterface
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
        ];
        return $attribute;
    }
}
