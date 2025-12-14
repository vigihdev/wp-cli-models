<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class MenuException extends WpCliModelException
{
    public const NOT_FOUND = 1001;
    public const INVALID_LOCATION = 1002;
    public const ALREADY_EXISTS = 1003;
    public const CREATE_FAILED = 1004;
    public const UPDATE_FAILED = 1005;
    public const DELETE_FAILED = 1006;

    public static function notFound(string $identifier): self
    {
        return new self(
            message: sprintf("Menu tidak ditemukan: %s", $identifier),
            context: [
                'menu_identifier' => $identifier,
            ],
            code: self::NOT_FOUND,
            solutions: [
                'Periksa apakah menu dengan identifier tersebut ada',
                'Gunakan wp menu list untuk melihat daftar menu'
            ]
        );
    }

    public static function invalidLocation(string $location): self
    {
        return new self(
            message: sprintf("Menu location tidak valid: %s", $location),
            context: [
                'location' => $location,
            ],
            code: self::INVALID_LOCATION,
            solutions: [
                'Gunakan location yang terdaftar di theme',
                'Periksa available locations dengan get_registered_nav_menus()'
            ]
        );
    }

    public static function alreadyExists(string $name): self
    {
        return new self(
            message: sprintf("Menu sudah ada dengan nama: %s", $name),
            context: [
                'menu_name' => $name,
            ],
            code: self::ALREADY_EXISTS,
            solutions: [
                'Gunakan nama menu yang berbeda',
                'Update menu yang sudah ada'
            ]
        );
    }

    public static function createFailed(string $name, string $error = ''): self
    {
        $message = sprintf("Gagal membuat menu: %s", $name);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'menu_name' => $name,
                'error' => $error,
            ],
            code: self::CREATE_FAILED,
            solutions: [
                'Periksa permission user',
                'Pastikan nama menu valid'
            ]
        );
    }

    public static function updateFailed(string $identifier, string $error = ''): self
    {
        $message = sprintf("Gagal mengupdate menu: %s", $identifier);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'menu_identifier' => $identifier,
                'error' => $error,
            ],
            code: self::UPDATE_FAILED,
            solutions: [
                'Periksa apakah menu masih ada',
                'Periksa permission user'
            ]
        );
    }

    public static function deleteFailed(string $identifier, string $error = ''): self
    {
        $message = sprintf("Gagal menghapus menu: %s", $identifier);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'menu_identifier' => $identifier,
                'error' => $error,
            ],
            code: self::DELETE_FAILED,
            solutions: [
                'Periksa apakah menu sedang digunakan',
                'Periksa permission user'
            ]
        );
    }
}
