<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Author\UserEntityDto;
use WP_User;

final class UserEntity
{

    /**
     * Mencari user berdasarkan ID atau username/email
     *
     * @param int|string $user ID user atau username/email
     * @return UserEntityDto|null Instance UserEntityDto jika user ditemukan, null jika tidak
     */
    public static function get(int|string $user): ?UserEntityDto
    {
        if (is_int($user) || is_numeric($user)) {
            $user_ = get_user_by('ID', (int) $user);
            return $user_ ? UserEntityDto::fromQuery($user_->data) : null;
        }

        if (is_string($user)) {
            if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
                $user_ = get_user_by('email', $user);
                return $user_ ? UserEntityDto::fromQuery($user_->data) : null;
            }
            $user_ = get_user_by('username', $user);
            return $user_ ? UserEntityDto::fromQuery($user_->data) : null;
        }

        return null;
    }

    /**
     * Mengambil semua user dalam bentuk array DTO
     * 
     * @return Collection<UserEntityDto> Array dari UserEntityDto  
     */
    public static function findAll(): Collection
    {
        $users = array_map(
            fn(WP_User $user) => UserEntityDto::fromQuery($user->data),
            get_users()
        );

        return new Collection($users);
    }

    /**
     * Mengambil satu user berdasarkan ID atau username/email
     * 
     * @param int|string $user ID user atau username/email
     * @return UserEntityDto|null Instance UserEntityDto jika user ditemukan, null jika tidak
     */
    public static function findOne(): ?UserEntityDto
    {
        return self::findAll()?->first() ?? null;
    }

    /**
     * Memeriksa apakah user dengan ID atau username/email tertentu ada dalam sistem
     *
     * @param int|string $user ID user atau username/email
     * @return bool True jika user ditemukan, false jika tidak
     */
    public static function exists(int|string $user): bool
    {
        return self::get($user) !== null;
    }
}
