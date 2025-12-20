<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Entities\Author;

use Vigihdev\WpCliModels\DTOs\Entities\BaseEntityDto;
use InvalidArgumentException;
use Vigihdev\WpCliModels\Contracts\Entities\Author\UserEntityInterface;
use WP_User;

final class UserEntityDto extends BaseEntityDto implements UserEntityInterface
{
    /**
     * Membuat instance objek UserEntityDto dengan parameter yang ditentukan
     *
     * @param int $id ID user
     * @param string $email Email user
     * @param string $userLogin Username user
     * @param string $firstname First name user
     * @param string $lastname Last name user
     * @param string $level Level user
     * @param string $nicename Nice name user
     * @param string $status Status user
     * @param string $url URL user
     * @param array $roles Roles user
     */
    public function __construct(
        private readonly int $id,
        private readonly string $email,
        private readonly string $userLogin,
        private readonly string $firstname,
        private readonly string $lastname,
        private readonly string $level,
        private readonly string $nicename,
        private readonly string $status,
        private readonly string $url,
        private readonly array $roles = [],
    ) {}

    /**
     * ID user
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Email user
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Username user
     */
    public function getUsername(): string
    {
        return $this->userLogin;
    }

    /**
     * First name user
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Last name user
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Level user
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Nice name user
     */
    public function getNicename(): string
    {
        return $this->nicename;
    }

    /**
     * Status user
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * URL user
     */
    public function getUrl(): string
    {
        return $this->url;
    }
    /**
     * Roles user
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Mengubah objek menjadi array
     *
     * @return array Array yang berisi data user
     */
    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'user_login' => $this->userLogin,
            'user_email' => $this->email,
            'user_firstname' => $this->firstname,
            'user_lastname' => $this->lastname,
            'user_level' => $this->level,
            'user_nicename' => $this->nicename,
            'user_status' => $this->status,
            'user_url' => $this->url,
            'roles' => $this->roles,
        ];
    }

    /**
     * Membuat instance dari query data
     *
     * @param mixed $data Data dari query
     * @return static Instance UserEntityDto
     */
    public static function fromQuery(mixed $data): static
    {

        if ($data instanceof WP_User) {
            $userData = $data->data;
            $data = array_merge(get_object_vars($userData), get_object_vars($data));
        }

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            $data = array_change_key_case($data, CASE_LOWER);
            foreach ($data as $key => $value) {
                if (! is_string($key)) {
                    continue;
                }

                if (!str_starts_with($key, 'user_')) {
                    continue;
                }

                if ($key === 'user_login') {
                    continue;
                }

                $key = str_replace('user_', '', $key);
                $data[$key] = (string) $value;
            }
        }

        return self::fromArray($data);
    }

    /**
     * Membuat instance dari array data
     *
     * @param array $data Data array user
     * @return static Instance UserEntityDto
     * @throws InvalidArgumentException Jika data tidak valid
     */
    public static function fromArray(array $data): static
    {

        if (!isset($data['id'])) {
            throw new InvalidArgumentException('ID is required');
        }

        return new static(
            id: (int) $data['id'],
            email: $data['email'] ?? '',
            userLogin: $data['user_login'] ?? '',
            firstname: $data['firstname'] ?? '',
            lastname: $data['lastname'] ?? '',
            level: $data['level'] ?? '',
            nicename: $data['nicename'] ?? '',
            status: $data['status'] ?? '',
            url: $data['url'] ?? '',
            roles: $data['roles'] ?? [],
        );
    }
}
