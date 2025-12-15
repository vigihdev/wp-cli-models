<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class UserException extends WpCliModelException
{
    public const USER_NOT_FOUND = 1001;
    public const INVALID_EMAIL = 1002;
    public const DUPLICATE_USERNAME = 1003;
    public const DUPLICATE_EMAIL = 1004;
    public const INVALID_ROLE = 1005;
    public const INVALID_PASSWORD = 1006;

    public static function userNotFound(int $userId): self
    {
        return new self(
            message: "User dengan ID {$userId} tidak ditemukan",
            context: ['user_id' => $userId],
            solutions: [
                'Verifikasi bahwa user ID ada di database',
                'Gunakan wp user list untuk melihat daftar user'
            ]
        );
    }

    public static function invalidEmail(string $email): self
    {
        return new self(
            message: "Alamat email tidak valid: {$email}",
            context: ['email' => $email],
            solutions: ['Berikan alamat email yang valid dengan format yang benar']
        );
    }

    public static function duplicateUsername(string $username): self
    {
        return new self(
            message: "Username '{$username}' sudah digunakan",
            context: ['username' => $username],
            solutions: [
                'Pilih username yang berbeda',
                'Gunakan wp user list untuk melihat username yang sudah ada'
            ]
        );
    }

    public static function duplicateEmail(string $email): self
    {
        return new self(
            message: "Email '{$email}' sudah terdaftar",
            context: ['email' => $email],
            solutions: ['Gunakan alamat email yang berbeda']
        );
    }

    public static function invalidRole(string $role): self
    {
        return new self(
            message: "Role '{$role}' tidak valid",
            context: ['role' => $role],
            solutions: [
                'Gunakan role yang valid: administrator, editor, author, contributor, subscriber',
                'Gunakan wp role list untuk melihat daftar role yang tersedia'
            ]
        );
    }

    public static function invalidPassword(string $reason): self
    {
        return new self(
            message: "Password tidak valid: {$reason}",
            context: ['reason' => $reason],
            solutions: [
                'Password harus minimal 8 karakter',
                'Gunakan kombinasi huruf besar, kecil, angka, dan simbol'
            ]
        );
    }

    public static function userCreationFailed(string $reason, array $context = []): self
    {
        return new self(
            message: "Gagal membuat user: {$reason}",
            context: $context,
            solutions: [
                'Periksa koneksi database',
                'Verifikasi semua field yang required sudah diisi'
            ]
        );
    }

    public static function userUpdateFailed(int $userId, string $reason): self
    {
        return new self(
            message: "Gagal mengupdate user ID {$userId}: {$reason}",
            context: ['user_id' => $userId, 'reason' => $reason],
            solutions: ['Periksa permission dan validitas data yang diupdate']
        );
    }

    public static function userDeletionFailed(int $userId, string $reason): self
    {
        return new self(
            message: "Gagal menghapus user ID {$userId}: {$reason}",
            context: ['user_id' => $userId, 'reason' => $reason],
            solutions: [
                'Pastikan user bukan super admin',
                'Verifikasi permission untuk menghapus user'
            ]
        );
    }

    public static function insufficientPermissions(int $userId, string $action): self
    {
        return new self(
            message: "User ID {$userId} tidak memiliki permission untuk: {$action}",
            context: ['user_id' => $userId, 'action' => $action],
            solutions: [
                'Gunakan user dengan role yang lebih tinggi',
                'Update capabilities user tersebut'
            ]
        );
    }

    public static function invalidMetaKey(string $metaKey): self
    {
        return new self(
            message: "Meta key '{$metaKey}' tidak valid",
            context: ['meta_key' => $metaKey],
            solutions: ['Gunakan meta key yang valid sesuai WordPress standards']
        );
    }

    public static function bulkOperationFailed(array $failedUsers, string $operation): self
    {
        return new self(
            message: "Operasi bulk '{$operation}' gagal untuk beberapa user",
            context: [
                'failed_users' => $failedUsers,
                'operation' => $operation,
                'total_failed' => count($failedUsers)
            ],
            solutions: [
                'Periksa log untuk detail error setiap user',
                'Coba operasi satu per satu untuk user yang gagal'
            ]
        );
    }
}
