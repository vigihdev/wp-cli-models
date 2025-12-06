<?php

namespace Vigihdev\WpCliModels\Tests\Units\Validators;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Menu\MenuArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;
use Vigihdev\WpCliModels\Tests\TestCase;
use Vigihdev\WpCliModels\Validators\Args\Menu\MenuArgsValidator;

/**
 * MenuArgsValidatorTest
 *
 * Unit test untuk MenuArgsValidator
 */
final class MenuArgsValidatorTest extends TestCase
{
    private MenuArgsValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new MenuArgsValidator();
    }

    #[Test]
    public function it_throws_exception_when_invalid_dto_is_passed_to_validate(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Expected %s, got stdClass', MenuArgsDto::class));

        $this->validator->validate(new \stdClass());
    }

    #[Test]
    public function it_throws_exception_when_name_is_empty(): void
    {
        $dto = new MenuArgsDto(name: '');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_name_exceeds_250_characters(): void
    {
        $dto = new MenuArgsDto(name: str_repeat('a', 251));

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_description_exceeds_500_characters(): void
    {
        $dto = new MenuArgsDto(
            name: 'Valid Menu',
            description: str_repeat('a', 501)
        );

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_passes_validation_with_valid_data(): void
    {
        $dto = new MenuArgsDto(
            name: 'Valid Menu',
            description: 'Valid description'
        );

        $this->validator->validate($dto);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_validates_for_update_with_valid_data(): void
    {
        $dto = new MenuArgsDto(name: 'Updated Menu');

        $this->validator->validateForUpdate($dto, 1);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_throws_exception_on_update_when_name_is_empty(): void
    {
        $dto = new MenuArgsDto(name: '');

        $this->expectException(ValidationException::class);
        $this->validator->validateForUpdate($dto, 1);
    }

    #[Test]
    public function it_validates_partial_fields(): void
    {
        $dto = new MenuArgsDto(name: 'Valid Menu');

        $this->validator->validatePartial($dto, ['name']);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_throws_exception_on_partial_validation_when_name_is_empty(): void
    {
        $dto = new MenuArgsDto(name: '');

        $this->expectException(ValidationException::class);
        $this->validator->validatePartial($dto, ['name']);
    }

    #[Test]
    public function it_throws_exception_on_partial_validation_when_description_is_too_long(): void
    {
        $dto = new MenuArgsDto(
            name: 'Valid Menu',
            description: str_repeat('a', 501)
        );

        $this->expectException(ValidationException::class);
        $this->validator->validatePartial($dto, ['description']);
    }

    #[Test]
    public function it_validates_single_field_name(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu name is required.');

        MenuArgsValidator::validateField('name', '');
    }

    #[Test]
    public function it_validates_single_field_name_too_long(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu name is too long.');

        MenuArgsValidator::validateField('name', str_repeat('a', 251));
    }

    #[Test]
    public function it_validates_single_field_slug(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu slug can only contain lowercase letters, numbers, and hyphens.');

        MenuArgsValidator::validateField('slug', 'Invalid Slug!');
    }

    #[Test]
    public function it_passes_single_field_validation(): void
    {
        MenuArgsValidator::validateField('name', 'valid-name');
        MenuArgsValidator::validateField('slug', 'valid-slug-123');

        $this->addToAssertionCount(1);
    }
}
