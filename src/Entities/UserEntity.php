<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

use WP_User;

final class UserEntity
{

    /**
     * Mencari user berdasarkan ID author
     *
     * @param int $id ID user yang akan dicari
     * @return WP_User|null Instance WP_User jika user ditemukan, null jika tidak
     */
    public static function findByAuthorId(int $id): ?WP_User
    {
        $user = get_user_by('ID', $id);

        if (!$user) {
            return null;
        }

        return $user;
    }

    /**
     * Mengambil satu user secara acak atau berdasarkan kriteria default
     *
     * @return WP_User Instance WP_User jika user ditemukan, null jika tidak ada user
     */
    public static function findOne(): WP_User
    {
        $users = get_users([
            'number' => 1,
            'orderby' => 'registered',
            'order' => 'ASC'
        ]);

        return $users[0];
    }

    /**
     * Memeriksa apakah user dengan ID tertentu ada dalam sistem
     *
     * @param int $id ID user yang akan diperiksa
     * @return bool True jika user ditemukan, false jika tidak
     */
    public static function hasExistUserId(int $id): bool
    {
        $user = get_user_by('ID', $id);

        return $user !== false;
    }
}
