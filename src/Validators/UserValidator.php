<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Exceptions\UserException;

final class UserValidator
{
    private array $data = [];
    private ?int $userId = null;

    private function __construct(
        array $data = [],
        ?int $userId = null
    ) {
        $this->data = $data;
        $this->userId = $userId;
    }

    /**
     * Static factory method untuk create validation
     */
    public static function validateCreate(array $data): self
    {
        return new self($data);
    }

    /**
     * Static factory method untuk update validation
     */
    public static function validateUpdate(int $userId, array $data): self
    {
        return new self($data, $userId);
    }

    /**
     * Static factory method untuk user ID validation
     */
    public static function validateUser(int $userId): self
    {
        return new self([], $userId);
    }

    /**
     * Validasi user exists
     */
    public function mustExist(): self
    {
        if ($this->userId === null || $this->userId <= 0) {
            throw UserException::userNotFound($this->userId ?? 0);
        }

        // Dalam implementasi nyata: cek database
        if (!get_user_by('ID', $this->userId)) {
            throw UserException::userNotFound($this->userId);
        }

        return $this;
    }

    /**
     * Validasi email valid
     */
    public function mustHaveValidEmail(): self
    {
        $email = $this->data['email'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw UserException::invalidEmail($email);
        }

        return $this;
    }

    /**
     * Validasi email unique
     */
    public function mustHaveUniqueEmail(): self
    {
        $email = $this->data['email'] ?? '';
        $this->mustHaveValidEmail();

        // Dalam implementasi nyata: cek database
        if (email_exists($email)) {
            throw UserException::duplicateEmail($email);
        }

        return $this;
    }

    /**
     * Validasi username unique
     */
    public function mustHaveUniqueUsername(): self
    {
        $username = $this->data['username'] ?? '';

        if (empty($username) || strlen($username) < 3) {
            throw UserException::duplicateUsername($username);
        }

        // Dalam implementasi nyata: cek database
        if (username_exists($username)) {
            throw UserException::duplicateUsername($username);
        }

        return $this;
    }

    /**
     * Validasi role valid
     */
    public function mustHaveValidRole(): self
    {
        $role = $this->data['role'] ?? '';

        $validRoles = [
            'administrator',
            'editor',
            'author',
            'contributor',
            'subscriber'
        ];

        if (!in_array($role, $validRoles, true)) {
            throw UserException::invalidRole($role);
        }

        return $this;
    }

    /**
     * Validasi password strong
     */
    public function mustHaveStrongPassword(): self
    {
        $password = $this->data['password'] ?? '';

        if (strlen($password) < 8) {
            throw UserException::invalidPassword('Password terlalu pendek');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw UserException::invalidPassword('Password harus mengandung huruf besar');
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw UserException::invalidPassword('Password harus mengandung huruf kecil');
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw UserException::invalidPassword('Password harus mengandung angka');
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            throw UserException::invalidPassword('Password harus mengandung karakter spesial');
        }

        return $this;
    }

    /**
     * Validasi required fields ada
     */
    public function mustHaveRequiredFields(array $requiredFields): self
    {
        foreach ($requiredFields as $field) {
            if (empty($this->data[$field])) {
                throw UserException::userCreationFailed(
                    "Field '{$field}' wajib diisi",
                    ['missing_field' => $field, 'data' => $this->data]
                );
            }
        }

        return $this;
    }

    /**
     * Validasi user permission
     */
    public function mustHavePermission(string $action): self
    {
        $this->mustExist();

        // Dalam implementasi nyata: cek permission
        if (!user_can($this->userId, $action)) {
            throw UserException::insufficientPermissions($this->userId, $action);
        }

        return $this;
    }

    /**
     * Validasi meta key valid
     */
    public function mustHaveValidMetaKeys(): self
    {
        if (!isset($this->data['meta']) || !is_array($this->data['meta'])) {
            return $this;
        }

        foreach (array_keys($this->data['meta']) as $metaKey) {
            if (empty($metaKey)) {
                throw UserException::invalidMetaKey($metaKey);
            }

            if (preg_match('/[^a-zA-Z0-9_-]/', $metaKey)) {
                throw UserException::invalidMetaKey($metaKey);
            }

            if (strpos($metaKey, '__') === 0) {
                throw UserException::invalidMetaKey($metaKey);
            }
        }

        return $this;
    }

    /**
     * Validasi user bukan super admin
     */
    public function mustNotBeSuperAdmin(): self
    {
        $this->mustExist();

        // Dalam implementasi nyata: cek super admin
        if (is_super_admin($this->userId)) {
            throw UserException::userDeletionFailed(
                $this->userId,
                'Tidak dapat menghapus super admin'
            );
        }

        return $this;
    }

    /**
     * Helper: Validasi lengkap untuk create user
     */
    public function forCreate(): self
    {
        return $this
            ->mustHaveRequiredFields(['username', 'email', 'password'])
            ->mustHaveUniqueUsername()
            ->mustHaveUniqueEmail()
            ->mustHaveStrongPassword()
            ->mustHaveValidMetaKeys();
    }

    /**
     * Helper: Validasi lengkap untuk update user
     */
    public function forUpdate(): self
    {
        $this->mustExist();

        if (isset($this->data['email'])) {
            $this->mustHaveValidEmail();
        }

        if (isset($this->data['password'])) {
            $this->mustHaveStrongPassword();
        }

        if (isset($this->data['role'])) {
            $this->mustHaveValidRole();
        }

        $this->mustHaveValidMetaKeys();

        return $this;
    }

    /**
     * Helper: Validasi lengkap untuk delete user
     */
    public function forDelete(): self
    {
        return $this
            ->mustExist()
            ->mustNotBeSuperAdmin()
            ->mustHavePermission('delete_users');
    }

    /**
     * Get validated data
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get user ID
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
