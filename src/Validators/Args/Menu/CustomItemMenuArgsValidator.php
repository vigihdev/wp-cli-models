<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Validators\ArgsValidatorInterface;
use Vigihdev\WpCliModels\DTOs\Args\Menu\CustomItemMenuArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;

/**
 * Class CustomItemMenuArgsValidator
 *
 * Validator untuk memvalidasi argumen item menu kustom
 */
final class CustomItemMenuArgsValidator implements ArgsValidatorInterface
{
    /**
     * Validasi CustomItemMenuArgsDto
     *
     * @param object $dto DTO yang akan divalidasi
     * @throws ValidationException Jika validasi gagal
     */
    public function validate(object $dto): void
    {
        $errors = [];

        if (!$dto instanceof CustomItemMenuArgsDto) {
            throw new \InvalidArgumentException(
                sprintf('Expected %s, got %s', CustomItemMenuArgsDto::class, get_class($dto))
            );
        }

        // Validasi 1: Menu identifier tidak boleh kosong
        if (empty($dto->getMenu())) {
            $errors['menu'] = 'Menu identifier cannot be empty.';
        }

        // Validasi 2: Title tidak boleh kosong
        if (empty($dto->getTitle())) {
            $errors['title'] = 'Menu item title cannot be empty.';
        } elseif (strlen($dto->getTitle()) > 250) {
            $errors['title'] = 'Menu item title cannot exceed 250 characters.';
        }

        // Validasi 3: Link tidak boleh kosong dan harus merupakan URL yang valid
        if (empty($dto->getLink())) {
            $errors['link'] = 'Menu item link cannot be empty.';
        } elseif (!filter_var($dto->getLink(), FILTER_VALIDATE_URL)) {
            $errors['link'] = 'Menu item link must be a valid URL.';
        }

        // Validasi 4: Parent ID harus merupakan angka positif jika diset
        if ($dto->getParentId() !== null && $dto->getParentId() <= 0) {
            $errors['parent_id'] = 'Parent ID must be a positive integer.';
        }

        // Validasi 5: Position harus merupakan angka non-negatif jika diset
        if ($dto->getPosition() !== null && $dto->getPosition() < 0) {
            $errors['position'] = 'Position must be a non-negative integer.';
        }

        // Validasi 6: Description tidak boleh melebihi 500 karakter jika diset
        if ($dto->getDescription() && strlen($dto->getDescription()) > 500) {
            $errors['description'] = 'Menu item description cannot exceed 500 characters.';
        }

        // Validasi 7: Attribute title tidak boleh melebihi 250 karakter jika diset
        if ($dto->getAttrTitle() && strlen($dto->getAttrTitle()) > 250) {
            $errors['attr_title'] = 'Attribute title cannot exceed 250 characters.';
        }

        // Validasi 8: Target harus merupakan nilai yang valid jika diset
        if ($dto->getTarget() !== null && !in_array($dto->getTarget(), ['_blank', '_self', '_parent', '_top'])) {
            $errors['target'] = 'Target must be one of: _blank, _self, _parent, or _top.';
        }

        // Jika ada error, throw exception
        if (!empty($errors)) {
            throw ValidationException::fromErrors($errors);
        }
    }

    /**
     * Validasi cepat untuk single field
     *
     * @param string $field Nama field yang akan divalidasi
     * @param mixed $value Nilai field yang akan divalidasi
     * @throws ValidationException Jika validasi field gagal
     */
    public static function validateField(string $field, mixed $value): void
    {
        switch ($field) {
            case 'menu':
                if (empty($value)) {
                    throw ValidationException::forField(
                        'menu',
                        'Menu identifier is required.'
                    );
                }
                break;

            case 'title':
                if (empty($value)) {
                    throw ValidationException::forField(
                        'title',
                        'Menu item title is required.'
                    );
                }

                if (is_string($value) && strlen($value) > 250) {
                    throw ValidationException::forField(
                        'title',
                        'Menu item title cannot exceed 250 characters.'
                    );
                }
                break;

            case 'link':
                if (empty($value)) {
                    throw ValidationException::forField(
                        'link',
                        'Menu item link is required.'
                    );
                }

                if (is_string($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    throw ValidationException::forField(
                        'link',
                        'Menu item link must be a valid URL.'
                    );
                }
                break;

            case 'parent_id':
                if ($value !== null && (!is_int($value) || $value <= 0)) {
                    throw ValidationException::forField(
                        'parent_id',
                        'Parent ID must be a positive integer.'
                    );
                }
                break;

            case 'position':
                if ($value !== null && (!is_int($value) || $value < 0)) {
                    throw ValidationException::forField(
                        'position',
                        'Position must be a non-negative integer.'
                    );
                }
                break;

            case 'description':
                if ($value !== null && is_string($value) && strlen($value) > 500) {
                    throw ValidationException::forField(
                        'description',
                        'Menu item description cannot exceed 500 characters.'
                    );
                }
                break;

            case 'attr_title':
                if ($value !== null && is_string($value) && strlen($value) > 250) {
                    throw ValidationException::forField(
                        'attr_title',
                        'Attribute title cannot exceed 250 characters.'
                    );
                }
                break;

            case 'target':
                if ($value !== null && !in_array($value, ['_blank', '_self', '_parent', '_top'])) {
                    throw ValidationException::forField(
                        'target',
                        'Target must be one of: _blank, _self, _parent, or _top.'
                    );
                }
                break;
        }
    }
}
