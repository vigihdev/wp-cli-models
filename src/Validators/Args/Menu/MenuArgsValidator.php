<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Validators\ArgsValidatorInterface;
use Vigihdev\WpCliModels\DTOs\Args\Menu\MenuArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;

final class MenuArgsValidator implements ArgsValidatorInterface
{
    /**
     * Validasi MenuArgsDto
     *
     * @param MenuArgsDto $dto
     * @throws ValidationException
     */
    public function validate(object $dto): void
    {
        $errors = [];

        if (!$dto instanceof MenuArgsDto) {
            throw new \InvalidArgumentException(
                sprintf('Expected %s, got %s', MenuArgsDto::class, get_class($dto))
            );
        }

        // Validasi 1: Nama menu tidak boleh kosong
        if (empty($dto->getName())) {
            $errors['name'] = 'Menu name cannot be empty.';
        } elseif (strlen($dto->getName()) > 250) {
            $errors['name'] = 'Menu name cannot exceed 250 characters.';
        }

        // Validasi 2: Cek apakah menu sudah ada
        if (empty($errors['name'])) {
            $existingMenu = wp_get_nav_menu_object($dto->getName());
            if ($existingMenu) {
                $errors['name'] = sprintf(
                    'Menu "%s" already exists (ID: %d).',
                    $dto->getName(),
                    $existingMenu->term_id
                );
            }
        }

        // Validasi 3: Validasi menu location jika ada
        if ($dto->getLocation()) {
            if (!is_string($dto->getLocation())) {
                $errors['location'] = 'Menu location must be a string.';
            } elseif (!array_key_exists($dto->getLocation(), $this->getRegisteredNavMenus())) {
                $errors['location'] = sprintf(
                    'Menu location "%s" is not registered. Available locations: %s',
                    $dto->getLocation(),
                    implode(', ', array_keys($this->getRegisteredNavMenus()))
                );
            }
        }

        // Validasi 4: Parent slug harus valid jika ada
        // if ($dto->getParentSlug() && !empty($dto->getName())) {
        //     $parentMenu = wp_get_nav_menu_object($dto->getParentSlug());
        //     if (!$parentMenu) {
        //         $errors['parent_slug'] = sprintf(
        //             'Parent menu "%s" does not exist.',
        //             $dto->getParentSlug()
        //         );
        //     }
        // }

        // Validasi 5: Menu description maksimal 500 karakter
        if ($dto->getDescription() && strlen($dto->getDescription()) > 500) {
            $errors['description'] = 'Menu description cannot exceed 500 characters.';
        }

        // Jika ada error, throw exception
        if (!empty($errors)) {
            throw ValidationException::fromErrors($errors);
        }
    }

    /**
     * Validasi untuk update menu (mengizinkan menu yang sudah ada)
     *
     * @param MenuArgsDto $dto
     * @param int|null $menuId ID menu yang sedang diupdate
     * @throws ValidationException
     */
    public function validateForUpdate(MenuArgsDto $dto, ?int $menuId = null): void
    {
        $errors = [];

        // Validasi 1: Nama menu tidak boleh kosong
        if (empty($dto->getName())) {
            $errors['name'] = 'Menu name cannot be empty.';
        } elseif (strlen($dto->getName()) > 250) {
            $errors['name'] = 'Menu name cannot exceed 250 characters.';
        }

        // Validasi 2: Cek apakah nama menu sudah dipakai oleh menu lain
        if (empty($errors['name']) && $menuId) {
            $existingMenu = wp_get_nav_menu_object($dto->getName());
            if ($existingMenu && $existingMenu->term_id !== $menuId) {
                $errors['name'] = sprintf(
                    'Menu name "%s" is already used by another menu (ID: %d).',
                    $dto->getName(),
                    $existingMenu->term_id
                );
            }
        }

        // Validasi location dan lainnya (sama seperti create)
        if ($dto->getLocation()) {
            if (!is_string($dto->getLocation())) {
                $errors['location'] = 'Menu location must be a string.';
            } elseif (!array_key_exists($dto->getLocation(), $this->getRegisteredNavMenus())) {
                $errors['location'] = sprintf(
                    'Menu location "%s" is not registered.',
                    $dto->getLocation()
                );
            }
        }

        // Jika ada error, throw exception
        if (!empty($errors)) {
            throw ValidationException::fromErrors($errors);
        }
    }

    /**
     * Validasi partial untuk field tertentu saja
     *
     * @param MenuArgsDto $dto
     * @param array<string> $fields Fields to validate
     * @throws ValidationException
     */
    public function validatePartial(MenuArgsDto $dto, array $fields): void
    {
        $errors = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'name':
                    if (empty($dto->getName())) {
                        $errors['name'] = 'Menu name cannot be empty.';
                    }
                    break;

                case 'location':
                    if ($dto->getLocation()) {
                        if (!array_key_exists($dto->getLocation(), $this->getRegisteredNavMenus())) {
                            $errors['location'] = 'Invalid menu location.';
                        }
                    }
                    break;

                case 'description':
                    if ($dto->getDescription() && strlen($dto->getDescription()) > 500) {
                        $errors['description'] = 'Description too long.';
                    }
                    break;
            }
        }

        if (!empty($errors)) {
            throw ValidationException::fromErrors($errors);
        }
    }

    /**
     * Get registered nav menus dengan caching
     *
     * @return array<string, string>
     */
    private function getRegisteredNavMenus(): array
    {
        static $navMenus = null;

        if ($navMenus === null) {
            $navMenus = get_registered_nav_menus() ?: [];
        }

        return $navMenus;
    }

    /**
     * Validasi cepat untuk single field
     *
     * @param string $field
     * @param mixed $value
     * @throws ValidationException
     */
    public static function validateField(string $field, mixed $value): void
    {
        switch ($field) {
            case 'name':
                if (empty($value)) {
                    throw ValidationException::forField(
                        'name',
                        'Menu name is required.'
                    );
                }
                if (strlen($value) > 250) {
                    throw ValidationException::forField(
                        'name',
                        'Menu name is too long.'
                    );
                }
                break;

            case 'slug':
                if (!empty($value) && !preg_match('/^[a-z0-9\-]+$/', $value)) {
                    throw ValidationException::forField(
                        'slug',
                        'Menu slug can only contain lowercase letters, numbers, and hyphens.'
                    );
                }
                break;
        }
    }
}
