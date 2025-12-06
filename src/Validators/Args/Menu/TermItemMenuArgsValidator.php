<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Args\Menu;

use Vigihdev\WpCliModels\Contracts\Validators\ArgsValidatorInterface;
use Vigihdev\WpCliModels\DTO\Args\Menu\TermItemMenuArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;

/**
 * Class TermItemMenuArgsValidator
 *
 * Validator untuk memvalidasi argumen perintah penambahan term item menu di WP-CLI
 */
final class TermItemMenuArgsValidator implements ArgsValidatorInterface
{
    /**
     * Validasi TermItemMenuArgsDto
     *
     * @param object $dto
     * @throws ValidationException
     */
    public function validate(object $dto): void
    {
        $errors = [];

        if (!$dto instanceof TermItemMenuArgsDto) {
            throw new \InvalidArgumentException(
                sprintf('Expected %s, got %s', TermItemMenuArgsDto::class, get_class($dto))
            );
        }

        // Validasi 1: Menu identifier tidak boleh kosong
        if (empty($dto->getMenu())) {
            $errors['menu'] = 'Menu identifier cannot be empty.';
        }

        // Validasi 2: Taxonomy tidak boleh kosong
        if (empty($dto->getTaxonomy())) {
            $errors['taxonomy'] = 'Taxonomy cannot be empty.';
        }

        // Validasi 3: Term ID harus positif
        if ($dto->getTermId() <= 0) {
            $errors['term_id'] = 'Term ID must be a positive integer.';
        }

        // Validasi 4: Position harus non-negatif jika ada
        if ($dto->getPosition() !== null && $dto->getPosition() < 0) {
            $errors['position'] = 'Position must be a non-negative integer.';
        }

        // Validasi 5: Parent ID harus positif jika ada
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

            case 'taxonomy':
                if (empty($value)) {
                    throw ValidationException::forField(
                        'taxonomy',
                        'Taxonomy is required.'
                    );
                }
                break;

            case 'termId':
                if (!is_int($value) || $value <= 0) {
                    throw ValidationException::forField(
                        'term_id',
                        'Term ID must be a positive integer.'
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
