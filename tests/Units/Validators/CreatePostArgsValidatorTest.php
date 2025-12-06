<?php

namespace Vigihdev\WpCliModels\Tests\Units\Validators;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Post\CreatePostArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;
use Vigihdev\WpCliModels\Tests\TestCase;
use Vigihdev\WpCliModels\Validators\Args\Post\CreatePostArgsValidator;

/**
 * CreatePostArgsValidatorTest
 *
 * Unit test untuk CreatePostArgsValidator
 */
final class CreatePostArgsValidatorTest extends TestCase
{
    private CreatePostArgsValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new CreatePostArgsValidator();
    }

    #[Test]
    public function it_throws_exception_when_invalid_dto_is_passed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Expected %s, got stdClass', CreatePostArgsDto::class));

        $this->validator->validate(new \stdClass());
    }

    #[Test]
    public function it_throws_exception_when_title_is_empty(): void
    {
        $dto = new CreatePostArgsDto(title: '', content: 'Content');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_title_exceeds_255_characters(): void
    {
        $dto = new CreatePostArgsDto(title: str_repeat('a', 256), content: 'Content');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_passes_validation_with_valid_data(): void
    {
        $dto = new CreatePostArgsDto(title: 'Valid Title', content: 'Valid content');

        $this->validator->validate($dto);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_validates_partial_fields(): void
    {
        $dto = new CreatePostArgsDto(title: 'Valid Title', content: 'Content');

        $this->validator->validatePartial($dto, ['title']);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_throws_exception_on_partial_validation_when_title_is_empty(): void
    {
        $dto = new CreatePostArgsDto(title: '', content: 'Content');

        $this->expectException(ValidationException::class);
        $this->validator->validatePartial($dto, ['title']);
    }

    #[Test]
    public function it_validates_single_field_title(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Post title is required.');

        CreatePostArgsValidator::validateField('title', '');
    }

    #[Test]
    public function it_validates_single_field_title_too_long(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Post title is too long.');

        CreatePostArgsValidator::validateField('title', str_repeat('a', 256));
    }

    #[Test]
    public function it_passes_single_field_validation(): void
    {
        CreatePostArgsValidator::validateField('title', 'Valid Title');
        CreatePostArgsValidator::validateField('content', 'Valid content');

        $this->addToAssertionCount(1);
    }
}
