<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators\Args\Post;

use Vigihdev\WpCliModels\Contracts\Validators\ArgsValidatorInterface;
use Vigihdev\WpCliModels\DTOs\Args\Post\CreatePostArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;

final class CreatePostArgsValidator implements ArgsValidatorInterface
{
    /**
     * Validasi CreatePostArgsDto
     *
     * @param CreatePostArgsDto $dto DTO yang akan divalidasi
     * @throws ValidationException Jika validasi gagal
     */
    public function validate(object $dto): void
    {
        $errors = [];

        if (!$dto instanceof CreatePostArgsDto) {
            throw new \InvalidArgumentException(
                sprintf('Expected %s, got %s', CreatePostArgsDto::class, get_class($dto))
            );
        }

        // Validasi title
        if (empty($dto->getTitle())) {
            $errors['title'] = 'Post title cannot be empty.';
        } elseif (strlen($dto->getTitle()) > 255) {
            $errors['title'] = 'Post title cannot exceed 255 characters.';
        }

        // Validasi content
        if (!is_string($dto->getContent())) {
            $errors['content'] = 'Post content must be a string.';
        }

        // Validasi author jika diset
        if ($dto->getAuthor() !== null) {
            if (!is_int($dto->getAuthor()) || $dto->getAuthor() <= 0) {
                $errors['author'] = 'Author ID must be a positive integer.';
            } elseif (!get_user_by('ID', $dto->getAuthor())) {
                $errors['author'] = 'Author with specified ID does not exist.';
            }
        }

        // Validasi status jika diset
        if ($dto->getStatus() !== null) {
            $validStatuses = ['publish', 'draft', 'pending', 'private', 'future', 'trash'];
            if (!in_array($dto->getStatus(), $validStatuses, true)) {
                $errors['status'] = sprintf(
                    'Invalid post status. Valid statuses: %s',
                    implode(', ', $validStatuses)
                );
            }
        }

        // Validasi type jika diset
        if ($dto->getType() !== null) {
            if (!post_type_exists($dto->getType())) {
                $errors['type'] = sprintf('Post type "%s" is not registered.', $dto->getType());
            }
        }

        // Validasi parent jika diset
        if ($dto->getParent() !== null) {
            if (!is_int($dto->getParent()) || $dto->getParent() < 0) {
                $errors['parent'] = 'Parent ID must be a non-negative integer.';
            } elseif ($dto->getParent() > 0 && !get_post($dto->getParent())) {
                $errors['parent'] = 'Parent post with specified ID does not exist.';
            }
        }

        // Validasi menu order jika diset
        if ($dto->getMenuOrder() !== null) {
            if (!is_int($dto->getMenuOrder()) || $dto->getMenuOrder() < 0) {
                $errors['menu_order'] = 'Menu order must be a non-negative integer.';
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
     * @param CreatePostArgsDto $dto DTO yang akan divalidasi
     * @param array<string> $fields Fields yang akan divalidasi
     * @throws ValidationException Jika validasi gagal
     */
    public function validatePartial(CreatePostArgsDto $dto, array $fields): void
    {
        $errors = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'title':
                    if (empty($dto->getTitle())) {
                        $errors['title'] = 'Post title cannot be empty.';
                    }
                    break;

                case 'content':
                    if (!is_string($dto->getContent())) {
                        $errors['content'] = 'Post content must be a string.';
                    }
                    break;

                case 'author':
                    if ($dto->getAuthor() !== null) {
                        if (!is_int($dto->getAuthor()) || $dto->getAuthor() <= 0) {
                            $errors['author'] = 'Author ID must be a positive integer.';
                        } elseif (!get_user_by('ID', $dto->getAuthor())) {
                            $errors['author'] = 'Author with specified ID does not exist.';
                        }
                    }
                    break;

                case 'status':
                    if ($dto->getStatus() !== null) {
                        $validStatuses = ['publish', 'draft', 'pending', 'private', 'future', 'trash'];
                        if (!in_array($dto->getStatus(), $validStatuses, true)) {
                            $errors['status'] = 'Invalid post status.';
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
            case 'title':
                if (empty($value)) {
                    throw ValidationException::forField(
                        'title',
                        'Post title is required.'
                    );
                }
                if (strlen($value) > 255) {
                    throw ValidationException::forField(
                        'title',
                        'Post title is too long.'
                    );
                }
                break;

            case 'content':
                if (!is_string($value)) {
                    throw ValidationException::forField(
                        'content',
                        'Post content must be a string.'
                    );
                }
                break;

            case 'author':
                if ($value !== null) {
                    if (!is_int($value) || $value <= 0) {
                        throw ValidationException::forField(
                            'author',
                            'Author ID must be a positive integer.'
                        );
                    } elseif (!get_user_by('ID', $value)) {
                        throw ValidationException::forField(
                            'author',
                            'Author with specified ID does not exist.'
                        );
                    }
                }
                break;

            case 'status':
                if ($value !== null) {
                    $validStatuses = ['publish', 'draft', 'pending', 'private', 'future', 'trash'];
                    if (!in_array($value, $validStatuses, true)) {
                        throw ValidationException::forField(
                            'status',
                            sprintf('Invalid post status. Valid statuses: %s', implode(', ', $validStatuses))
                        );
                    }
                }
                break;
        }
    }
}
