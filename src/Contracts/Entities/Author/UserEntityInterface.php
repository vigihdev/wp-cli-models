<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Entities\Author;

/**
 * Interface UserEntityInterface
 *
 * Interface untuk mendefinisikan struktur data user entity
 */
interface UserEntityInterface
{

    /**
     * ID user
     */
    public function getId(): int;

    /**
     * Email user
     */
    public function getEmail(): string;

    public function getUsername(): string;

    /**
     * First name user
     */
    public function getFirstname(): string;

    /**
     * Last name user
     */
    public function getLastname(): string;

    /**
     * Level user
     */
    public function getLevel(): string;

    /**
     * Nice name user
     */
    public function getNicename(): string;

    /**
     * Status user
     */
    public function getStatus(): string;

    /**
     * URL user
     */
    public function getUrl(): string;

    /**
     * Roles user
     */
    public function getRoles(): array;
}
