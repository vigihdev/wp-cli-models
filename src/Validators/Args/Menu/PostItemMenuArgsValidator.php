<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Validators\ArgsValidatorInterface;
use Vigihdev\WpCliModels\DTOs\Args\Menu\PostItemMenuArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;

/**
 * Class PostItemMenuArgsValidator
 *
 * Validator untuk memvalidasi argumen perintah penambahan post item menu di WP-CLI
 */
final class PostItemMenuArgsValidator implements ArgsValidatorInterface
{
    /**
     * Validasi PostItemMenuArgsDto
     *
     * @param object $dto
     * @throws ValidationException
     */
    public function validate(object $dto): void
    {
        $errors = [];

        if (!$dto instanceof PostItemMenuArgsDto) {
            throw new \InvalidArgumentException(
                sprintf('Expected %s, got %s', PostItemMenuArgsDto::class, get_class($dto))
            );
        }

        // Validasi 1: Menu identifier tidak boleh kosong
        if (empty($dto->getMenu())) {
            $errors['menu'] = 'Menu identifier cannot be empty.';
        }

        // Validasi 2: Post ID harus positif
        if ($dto->getPostId() <= 0) {
            $errors['post_id'] = 'Post ID must be a positive integer.';
        }

        // Validasi 3: Position harus non-negatif jika ada
        if ($dto->getPosition() !== null && $dto->getPosition() < 0) {
            $errors['position'] = 'Position must be a non-negative integer.';
        }

        // Validasi 4: Parent ID harus positif jika ada
        if ($dto->getParentId() !== null && $dto->getParentId() <= 0) {
            $errors['parent_id'] = 'Parent ID must be a positive integer.';
        }

        // Jika ada error, throw exception
        if (!empty($errors)) {
            throw ValidationException::fromErrors($errors);
        }
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
            case 'menu':
                if (empty($value)) {
                    throw ValidationException::forField(
                        'menu',
                        'Menu identifier is required.'
                    );
                }
                break;

            case 'postId':
                if (!is_int($value) || $value <= 0) {
                    throw ValidationException::forField(
                        'post_id',
                        'Post ID must be a positive integer.'
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

            case 'parentId':
                if ($value !== null && (!is_int($value) || $value <= 0)) {
                    throw ValidationException::forField(
                        'parent_id',
                        'Parent ID must be a positive integer.'
                    );
                }
                break;
        }
    }
}
