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

    private ?int $itemId;
    private ?int $parentId;
    private ?string $itemTitle;

    public function __construct(
        string $message,
        ?int $itemId = null,
        ?int $parentId = null,
        ?string $itemTitle = null,
        array $context = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->itemId = $itemId;
        $this->parentId = $parentId;
        $this->itemTitle = $itemTitle;

        $context['item_id'] = $itemId;
        $context['parent_id'] = $parentId;
        $context['item_title'] = $itemTitle;

        parent::__construct($message, $context, $code, $previous);
    }

    public static function notFound(int $itemId): self
    {
        return new self(
            sprintf("Menu item tidak ditemukan dengan ID: %d", $itemId),
            $itemId,
            null,
            null,
            [],
            self::NOT_FOUND
        );
    }

    public static function parentNotFound(int $parentId, int $menuId): self
    {
        return new self(
            sprintf("Parent item tidak ditemukan: ID %d di menu %d", $parentId, $menuId),
            null,
            $parentId,
            null,
            ['menu_id' => $menuId],
            self::PARENT_NOT_FOUND
        );
    }

    public static function invalidParent(int $itemId): self
    {
        return new self(
            sprintf("Item ID %d bukan parent item yang valid", $itemId),
            $itemId,
            null,
            null,
            [],
            self::INVALID_PARENT
        );
    }

    public static function duplicateTitle(string $title, int $parentId): self
    {
        return new self(
            sprintf("Menu item dengan title '%s' sudah ada di parent %d", $title, $parentId),
            null,
            $parentId,
            $title,
            [],
            self::DUPLICATE_TITLE
        );
    }

    public static function createFailed(string $title, string $error = ''): self
    {
        return new self(
            sprintf("Gagal membuat menu item: %s. Error: %s", $title, $error),
            null,
            null,
            $title,
            ['error' => $error],
            self::CREATE_FAILED
        );
    }

    public static function invalidType(string $type, array $allowedTypes): self
    {
        return new self(
            sprintf(
                "Tipe menu item tidak valid: %s. Tipe yang diperbolehkan: %s",
                $type,
                implode(', ', $allowedTypes)
            ),
            null,
            null,
            null,
            ['type' => $type, 'allowed_types' => $allowedTypes],
            self::INVALID_TYPE
        );
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function getItemTitle(): ?string
    {
        return $this->itemTitle;
    }
}
