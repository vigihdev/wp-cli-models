<?php

namespace Vigihdev\WpCliModels\Tests\Units\Validators;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Menu\CustomItemMenuArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;
use Vigihdev\WpCliModels\Tests\TestCase;
use Vigihdev\WpCliModels\Validators\Args\Menu\CustomItemMenuArgsValidator;

/**
 * CustomItemMenuArgsValidatorTest
 *
 * Unit test untuk CustomItemMenuArgsValidator
 */
final class CustomItemMenuArgsValidatorTest extends TestCase
{
    private CustomItemMenuArgsValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new CustomItemMenuArgsValidator();
    }

    #[Test]
    public function it_throws_exception_when_invalid_dto_is_passed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Expected %s, got stdClass', CustomItemMenuArgsDto::class));

        $this->validator->validate(new \stdClass());
    }

    #[Test]
    public function it_throws_exception_when_menu_is_empty(): void
    {
        $dto = new CustomItemMenuArgsDto(menu: '', title: 'Title', link: 'https://example.com');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_title_is_empty(): void
    {
        $dto = new CustomItemMenuArgsDto(menu: 'primary', title: '', link: 'https://example.com');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_title_exceeds_250_characters(): void
    {
        $dto = new CustomItemMenuArgsDto(menu: 'primary', title: str_repeat('a', 251), link: 'https://example.com');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_link_is_empty(): void
    {
        $dto = new CustomItemMenuArgsDto(menu: 'primary', title: 'Title', link: '');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_link_is_invalid_url(): void
    {
        $dto = new CustomItemMenuArgsDto(menu: 'primary', title: 'Title', link: 'not-a-valid-url');

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_passes_validation_with_valid_data(): void
    {
        $dto = new CustomItemMenuArgsDto(menu: 'primary', title: 'Valid Title', link: 'https://example.com');

        $this->validator->validate($dto);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_validates_single_field_menu(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu identifier is required.');

        CustomItemMenuArgsValidator::validateField('menu', '');
    }

    #[Test]
    public function it_validates_single_field_title(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu item title is required.');

        CustomItemMenuArgsValidator::validateField('title', '');
    }

    #[Test]
    public function it_validates_single_field_title_too_long(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu item title cannot exceed 250 characters.');

        CustomItemMenuArgsValidator::validateField('title', str_repeat('a', 251));
    }

    #[Test]
    public function it_validates_single_field_link(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu item link is required.');

        CustomItemMenuArgsValidator::validateField('link', '');
    }

    #[Test]
    public function it_validates_single_field_link_invalid_url(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu item link must be a valid URL.');

        CustomItemMenuArgsValidator::validateField('link', 'invalid-url');
    }

    #[Test]
    public function it_passes_single_field_validation(): void
    {
        CustomItemMenuArgsValidator::validateField('menu', 'primary');
        CustomItemMenuArgsValidator::validateField('title', 'Valid Title');
        CustomItemMenuArgsValidator::validateField('link', 'https://example.com');

        $this->addToAssertionCount(1);
    }
}
