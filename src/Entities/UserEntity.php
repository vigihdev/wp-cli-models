<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Author\UserEntityDto;
use WP_Error;
use WP_User;

final class UserEntity
{

    /**
     * Mencari user berdasarkan ID atau username/email/nickname
     *
     * @param int|string $user ID user atau username/email/nickname 
     * @return UserEntityDto|null Instance UserEntityDto jika user ditemukan, null jika tidak
     */
    public static function get(int|string $user): ?UserEntityDto
    {
        if (is_int($user) || is_numeric($user)) {
            $user_ = get_user_by('ID', (int) $user);
            return $user_ ? UserEntityDto::fromQuery($user_) : null;
        }

        if (is_string($user)) {
            if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
                $user_ = get_user_by('email', $user);
                return $user_ ? UserEntityDto::fromQuery($user_) : null;
            }

            $user_ = get_user_by('login', $user);
            return $user_ ? UserEntityDto::fromQuery($user_) : null;
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
            fn(WP_User $user) => UserEntityDto::fromQuery($user),
            get_users()
        );

        return new Collection($users);
    }

    /**
     * Mengambil satu user berdasarkan ID atau username/email/nickname
     * 
     * @param int|string $user ID user atau username/email
     * @return UserEntityDto|null Instance UserEntityDto jika user ditemukan, null jika tidak
     */
    public static function findOne(): ?UserEntityDto
    {
        return self::findAll()?->first() ?? null;
    }

    /**
     * Memeriksa apakah user dengan ID atau username/email/nickname tertentu ada dalam sistem
     *
     * @param int|string $user ID user atau username/email/nickname
     * @return bool True jika user ditemukan, false jika tidak
     */
    public static function exists(int|string $user): bool
    {
        return self::get($user) !== null;
    }

    /**
     * Membuat user baru dalam sistem
     * 
     * @param string $username Username user
     * @param string $password Password user
     * @param string $email Email user
     * @return int|WP_Error ID user yang dibuat, WP_Error jika gagal
     */
    public static function create(string $username, string $password, string $email): int|WP_Error
    {
        return wp_create_user($username, $password, $email);
    }

    /**
     * Menghapus user dari sistem berdasarkan ID
     * 
     * @param int $id ID user
     * @return bool True jika berhasil dihapus, false jika gagal
     */
    public static function delete(int $id): bool
    {
        return (bool) wp_delete_user($id);
    }

    /**
     * Memperbarui data user 
     * 
     * ```php
     * $userData = [
     *   'ID' => 1,
     *   'user_pass' => 'new_password',
     *   'user_login' => 'new_username',
     *   'user_nicename' => 'new_nicename',
     *   'user_url' => 'new_url',
     *   'user_email' => 'new_email',
     *   'display_name' => 'new_display_name',
     *   'nickname' => 'new_nickname',
     *   'first_name' => 'new_first_name',
     *   'last_name' => 'new_last_name',
     *   'description' => 'new_description',
     *   'rich_editing' => 'true',
     *   'syntax_highlighting' => 'true',
     *   'comment_shortcuts' => 'true',
     *   'admin_color' => 'fresh',
     *   'use_ssl' => true,
     *   'user_registered' => '2023-01-01 00:00:00',
     *   'user_activation_key' => '',
     *   'spam' => false,
     *   'show_admin_bar_front' => 'true',
     *   'role' => 'subscriber',
     *   'locale' => 'en_US',
     *   'meta_input' => [],
     * ];
     * 
     * ```
     * @param array|WP_User $userData Data user yang akan diperbarui ID 
     * @return int|WP_Error ID user yang diperbarui, WP_Error jika gagal
     */
    public static function update(array|WP_User $userData): int|WP_Error
    {
        return wp_update_user($userData);
    }
}
