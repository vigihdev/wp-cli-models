<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\UserFieldInterface;

final class UserFieldDto extends BaseFieldDto implements UserFieldInterface
{
    public function __construct(
        private readonly string $username,
        private readonly string $email,
    ) {}

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {

        return $this->email;
    }


    /**
     * Mengkonversi objek UserFieldDto menjadi array
     *
     * @return array<string, mixed> Array asosiatif yang berisi data term
     */
    public function toArray(): array
    {
        return array_filter([
            'username' => $this->getUsername(),
            'email' => $this->getEmail()
        ], function ($value) {
            return $value !== null;
        });
    }

    public static function fromArray(array $data): static
    {
        return new self(
            username: (string) ($data['username'] ?? ''),
            email: isset($data['email']) ? (string) $data['email'] : '',
        );
    }
}
