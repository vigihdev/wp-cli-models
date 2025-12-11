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

    private ?string $menuIdentifier;
    private ?int $menuId;

    public function __construct(
        string $message,
        ?string $menuIdentifier = null,
        ?int $menuId = null,
        array $context = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->menuIdentifier = $menuIdentifier;
        $this->menuId = $menuId;

        $context['menu_identifier'] = $menuIdentifier;
        $context['menu_id'] = $menuId;

        parent::__construct($message, $context, $code, $previous);
    }

    public static function notFound(string $identifier): self
    {
        return new self(
            sprintf("Menu tidak ditemukan: %s", $identifier),
            $identifier,
            null,
            [],
            self::NOT_FOUND
        );
    }

    public static function invalidLocation(string $location): self
    {
        return new self(
            sprintf("Menu location tidak valid: %s", $location),
            $location,
            null,
            [],
            self::INVALID_LOCATION
        );
    }

    public static function alreadyExists(string $name): self
    {
        return new self(
            sprintf("Menu sudah ada dengan nama: %s", $name),
            $name,
            null,
            [],
            self::ALREADY_EXISTS
        );
    }

    public static function createFailed(string $name, string $error = ''): self
    {
        return new self(
            sprintf("Gagal membuat menu: %s. Error: %s", $name, $error),
            $name,
            null,
            ['error' => $error],
            self::CREATE_FAILED
        );
    }

    public function getMenuIdentifier(): ?string
    {
        return $this->menuIdentifier;
    }

    public function getMenuId(): ?int
    {
        return $this->menuId;
    }
}
