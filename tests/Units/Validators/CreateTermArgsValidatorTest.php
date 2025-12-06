<?php

namespace Vigihdev\WpCliModels\Tests\Units\Validators;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Term\CreateTermArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;
use Vigihdev\WpCliModels\Tests\TestCase;
use Vigihdev\WpCliModels\Validators\Args\Term\CreateTermArgsValidator;

/**
 * CreateTermArgsValidatorTest
 *
 * Unit test untuk CreateTermArgsValidator
 */
final class CreateTermArgsValidatorTest extends TestCase
{
    private CreateTermArgsValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new CreateTermArgsValidator();
    }

    #[Test]
    public function it_throws_exception_when_invalid_dto_is_passed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Expected %s, got stdClass', CreateTermArgsDto::class));

        $this->validator->validate(new \stdClass());
    }

    #[Test]
    public function it_throws_exception_when_term_name_is_empty(): void
    {
        $dto = new CreateTermArgsDto(term: '', taxonomy: 'category');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_term_name_exceeds_200_characters(): void
    {
        $dto = new CreateTermArgsDto(term: str_repeat('a', 201), taxonomy: 'category');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_taxonomy_is_empty(): void
    {
        $dto = new CreateTermArgsDto(term: 'Valid Term', taxonomy: '');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_passes_validation_with_valid_data(): void
    {
        $dto = new CreateTermArgsDto(term: 'Valid Term', taxonomy: 'category');

        $this->validator->validate($dto);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_validates_for_update_with_valid_data(): void
    {
        $dto = new CreateTermArgsDto(term: 'Updated Term', taxonomy: 'category');

        $this->validator->validateForUpdate($dto, 1);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_validates_partial_fields(): void
    {
        $dto = new CreateTermArgsDto(term: 'Valid Term', taxonomy: 'category');

        $this->validator->validatePartial($dto, ['name']);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_throws_exception_on_partial_validation_when_name_is_empty(): void
    {
        $dto = new CreateTermArgsDto(term: '', taxonomy: 'category');

        $this->expectException(ValidationException::class);
        $this->validator->validatePartial($dto, ['name']);
    }

    #[Test]
    public function it_validates_single_field_name(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Term name is required.');

        CreateTermArgsValidator::validateField('name', '');
    }

    #[Test]
    public function it_validates_single_field_name_too_long(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Term name is too long.');

        CreateTermArgsValidator::validateField('name', str_repeat('a', 201));
    }

    #[Test]
    public function it_passes_single_field_validation(): void
    {
        CreateTermArgsValidator::validateField('name', 'Valid Term');
        CreateTermArgsValidator::validateField('slug', 'valid-slug');

        $this->addToAssertionCount(1);
    }
}
