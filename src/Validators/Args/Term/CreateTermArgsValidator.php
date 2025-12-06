<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Args\Term;

use Vigihdev\WpCliModels\Contracts\Validators\ArgsValidatorInterface;
use Vigihdev\WpCliModels\DTOs\Args\Term\CreateTermArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;

final class CreateTermArgsValidator implements ArgsValidatorInterface
{
    /**
     * Validasi CreateTermArgsDto
     *
     * @param CreateTermArgsDto $dto DTO yang akan divalidasi
     * @throws ValidationException Jika validasi gagal
     */
    public function validate(object $dto): void
    {
        $errors = [];

        if (!$dto instanceof CreateTermArgsDto) {
            throw new \InvalidArgumentException(
                sprintf('Expected %s, got %s', CreateTermArgsDto::class, get_class($dto))
            );
        }

        // Validasi 1: Nama term tidak boleh kosong
        if (empty($dto->getTerm())) {
            $errors['name'] = 'Term name cannot be empty.';
        } elseif (strlen($dto->getTerm()) > 200) {
            $errors['name'] = 'Term name cannot exceed 200 characters.';
        }

        // Validasi 2: Taxonomy harus diset dan terdaftar
        if (empty($dto->getTaxonomy())) {
            $errors['taxonomy'] = 'Taxonomy is required.';
        } elseif (!taxonomy_exists($dto->getTaxonomy())) {
            $errors['taxonomy'] = sprintf('Taxonomy "%s" is not registered.', $dto->getTaxonomy());
        }

        // Validasi 3: Slug tidak boleh terlalu panjang
        if ($dto->getSlug() !== null && strlen($dto->getSlug()) > 200) {
            $errors['slug'] = 'Term slug cannot exceed 200 characters.';
        }

        // Validasi 4: Description tidak boleh terlalu panjang
        if ($dto->getDescription() !== null && strlen($dto->getDescription()) > 5000) {
            $errors['description'] = 'Term description cannot exceed 5000 characters.';
        }

        // Validasi 5: Parent term harus valid jika diset
        if ($dto->getParent() !== null && $dto->getParent() > 0) {
            if (!term_exists($dto->getParent(), $dto->getTaxonomy())) {
                $errors['parent'] = 'Parent term does not exist in the specified taxonomy.';
            }
        }

        // Jika ada error, throw exception
        if (!empty($errors)) {
            throw ValidationException::fromErrors($errors);
        }
    }

    /**
     * Validasi untuk update term (mengizinkan term yang sudah ada)
     *
     * @param CreateTermArgsDto $dto DTO yang akan divalidasi
     * @param int|null $termId ID term yang sedang diupdate
     * @throws ValidationException Jika validasi gagal
     */
    public function validateForUpdate(CreateTermArgsDto $dto, ?int $termId = null): void
    {
        $errors = [];

        // Validasi 1: Nama term tidak boleh kosong
        if (empty($dto->getTerm())) {
            $errors['name'] = 'Term name cannot be empty.';
        } elseif (strlen($dto->getTerm()) > 200) {
            $errors['name'] = 'Term name cannot exceed 200 characters.';
        }

        // Validasi 2: Taxonomy harus diset dan terdaftar
        if (empty($dto->getTaxonomy())) {
            $errors['taxonomy'] = 'Taxonomy is required.';
        } elseif (!taxonomy_exists($dto->getTaxonomy())) {
            $errors['taxonomy'] = sprintf('Taxonomy "%s" is not registered.', $dto->getTaxonomy());
        }

        // Validasi 3: Slug tidak boleh terlalu panjang
        if ($dto->getSlug() !== null && strlen($dto->getSlug()) > 200) {
            $errors['slug'] = 'Term slug cannot exceed 200 characters.';
        }

        // Validasi 4: Description tidak boleh terlalu panjang
        if ($dto->getDescription() !== null && strlen($dto->getDescription()) > 5000) {
            $errors['description'] = 'Term description cannot exceed 5000 characters.';
        }

        // Validasi 5: Parent term harus valid jika diset
        if ($dto->getParent() !== null && $dto->getParent() > 0) {
            // Jika sedang mengupdate, pastikan parent bukan dirinya sendiri
            if ($termId && $dto->getParent() == $termId) {
                $errors['parent'] = 'Term cannot be its own parent.';
            } elseif (!term_exists($dto->getParent(), $dto->getTaxonomy())) {
                $errors['parent'] = 'Parent term does not exist in the specified taxonomy.';
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
     * @param CreateTermArgsDto $dto DTO yang akan divalidasi
     * @param array<string> $fields Fields yang akan divalidasi
     * @throws ValidationException Jika validasi gagal
     */
    public function validatePartial(CreateTermArgsDto $dto, array $fields): void
    {
        $errors = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'name':
                    if (empty($dto->getTerm())) {
                        $errors['name'] = 'Term name cannot be empty.';
                    }
                    break;

                case 'taxonomy':
                    if (empty($dto->getTaxonomy())) {
                        $errors['taxonomy'] = 'Taxonomy is required.';
                    } elseif (!taxonomy_exists($dto->getTaxonomy())) {
                        $errors['taxonomy'] = 'Taxonomy is not registered.';
                    }
                    break;

                case 'slug':
                    if ($dto->getSlug() !== null && strlen($dto->getSlug()) > 200) {
                        $errors['slug'] = 'Term slug is too long.';
                    }
                    break;

                case 'description':
                    if ($dto->getDescription() !== null && strlen($dto->getDescription()) > 5000) {
                        $errors['description'] = 'Description too long.';
                    }
                    break;

                case 'parent':
                    if ($dto->getParent() !== null && $dto->getParent() > 0) {
                        if (!term_exists($dto->getParent(), $dto->getTaxonomy())) {
                            $errors['parent'] = 'Parent term does not exist.';
                        }
                    }
                    break;
            }
        }

        if (!empty($errors)) {
            throw ValidationException::fromErrors($errors);
        }
    }

    /**
     * Validasi cepat untuk single field
     *
     * @param string $field Nama field yang akan divalidasi
     * @param mixed $value Nilai field yang akan divalidasi
     * @throws ValidationException Jika validasi gagal
     */
    public static function validateField(string $field, mixed $value): void
    {
        switch ($field) {
            case 'name':
                if (empty($value)) {
                    throw ValidationException::forField(
                        'name',
                        'Term name is required.'
                    );
                }
                if (strlen($value) > 200) {
                    throw ValidationException::forField(
                        'name',
                        'Term name is too long.'
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
                if (!taxonomy_exists($value)) {
                    throw ValidationException::forField(
                        'taxonomy',
                        sprintf('Taxonomy "%s" is not registered.', $value)
                    );
                }
                break;

            case 'slug':
                if (!empty($value) && strlen($value) > 200) {
                    throw ValidationException::forField(
                        'slug',
                        'Term slug is too long.'
                    );
                }
                break;
        }
    }
}
