<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class MenuItemException extends WpCliModelException
{
    public const NOT_FOUND = 2001;
    public const PARENT_NOT_FOUND = 2002;
    public const INVALID_PARENT = 2003;
    public const DUPLICATE_TITLE = 2004;
    public const CREATE_FAILED = 2005;
    public const UPDATE_FAILED = 2006;
    public const DELETE_FAILED = 2007;
    public const INVALID_TYPE = 2008;
    public const INVALID_POSITION = 2009;
    public const DUPLICATE_LINK = 2010;


    public static function notFound(int $itemId): self
    {
        return new self(
            message: sprintf("Menu item tidak ditemukan dengan ID: %d", $itemId),
            context: [
                'item_id' => $itemId,
            ],
            code: self::NOT_FOUND,
            solutions: [
                'Periksa apakah menu item dengan ID tersebut ada',
                'Gunakan wp menu item list untuk melihat daftar menu items'
            ]
        );
    }

    public static function parentNotFound(int $parentId, int $menuId): self
    {
        return new self(
            message: sprintf("Parent item tidak ditemukan: ID %d di menu %d", $parentId, $menuId),
            context: [
                'parent_id' => $parentId,
                'menu_id' => $menuId,
            ],
            code: self::PARENT_NOT_FOUND,
            solutions: [
                'Periksa apakah parent item masih ada',
                'Gunakan ID parent yang valid atau null untuk top-level item'
            ]
        );
    }

    public static function invalidParent(int $itemId): self
    {
        return new self(
            message: sprintf("Item ID %d bukan parent item yang valid", $itemId),
            context: [
                'item_id' => $itemId,
            ],
            code: self::INVALID_PARENT,
            solutions: [
                'Pastikan parent item bukan child dari item yang akan dibuat',
                'Hindari circular reference dalam menu hierarchy'
            ]
        );
    }

    public static function notSameAsType(string $type, string $expectedType): self
    {
        return new self(
            message: sprintf("Tipe menu item '%s' tidak sama dengan tipe yang diharapkan '%s'", $type, $expectedType),
            context: [
                'item_type' => $type,
                'expected_type' => $expectedType,
            ],
            code: self::INVALID_TYPE,
            solutions: [
                'Gunakan tipe menu item yang valid',
                'Lihat daftar tipe menu item yang didukung'
            ]
        );
    }

    public static function duplicateTitle(string $title, int $parentId, array $context = []): self
    {
        return new self(
            message: sprintf("Menu item dengan title '%s' sudah ada di parent %d", $title, $parentId),
            context: [
                'item_title' => $title,
                'parent_id' => $parentId,
                ...$context,
            ],
            code: self::DUPLICATE_TITLE,
            solutions: [
                'Gunakan title yang berbeda',
                'Update menu item yang sudah ada'
            ]
        );
    }

    public static function duplicateLink(string $link, int $parentId, array $context = []): self
    {
        return new self(
            message: sprintf("Menu item dengan link '%s' sudah ada di parent %d", $link, $parentId),
            context: [
                'item_link' => $link,
                'parent_id' => $parentId,
                ...$context,
            ],
            code: self::DUPLICATE_LINK,
            solutions: [
                'Gunakan link yang berbeda',
                'Update menu item yang sudah ada'
            ]
        );
    }

    public static function createFailed(string $title, string $error = ''): self
    {
        $message = sprintf("Gagal membuat menu item: %s", $title);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'item_title' => $title,
                'error' => $error,
            ],
            code: self::CREATE_FAILED,
            solutions: [
                'Periksa permission user',
                'Pastikan data menu item valid'
            ]
        );
    }

    public static function updateFailed(int $itemId, string $error = ''): self
    {
        $message = sprintf("Gagal mengupdate menu item ID: %d", $itemId);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'item_id' => $itemId,
                'error' => $error,
            ],
            code: self::UPDATE_FAILED,
            solutions: [
                'Periksa apakah menu item masih ada',
                'Periksa permission user'
            ]
        );
    }

    public static function deleteFailed(int $itemId, string $error = ''): self
    {
        $message = sprintf("Gagal menghapus menu item ID: %d", $itemId);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'item_id' => $itemId,
                'error' => $error,
            ],
            code: self::DELETE_FAILED,
            solutions: [
                'Periksa apakah menu item memiliki child items',
                'Periksa permission user'
            ]
        );
    }

    public static function invalidType(string $type, array $allowedTypes): self
    {
        return new self(
            message: sprintf(
                "Tipe menu item tidak valid: %s. Tipe yang diperbolehkan: %s",
                $type,
                implode(', ', $allowedTypes)
            ),
            context: [
                'type' => $type,
                'allowed_types' => $allowedTypes,
            ],
            code: self::INVALID_TYPE,
            solutions: [
                'Gunakan salah satu tipe: ' . implode(', ', $allowedTypes),
                'Periksa dokumentasi untuk tipe menu item yang valid'
            ]
        );
    }

    public static function invalidPosition(int $position): self
    {
        return new self(
            message: sprintf("Posisi menu item tidak valid: %d", $position),
            context: [
                'position' => $position,
            ],
            code: self::INVALID_POSITION,
            solutions: [
                'Gunakan posisi >= 0',
                'Posisi akan otomatis disesuaikan jika melebihi jumlah items'
            ]
        );
    }
}
