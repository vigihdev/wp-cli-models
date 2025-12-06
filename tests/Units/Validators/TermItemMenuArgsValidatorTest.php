<?php

namespace Vigihdev\WpCliModels\Tests\Units\Validators;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Menu\TermItemMenuArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;
use Vigihdev\WpCliModels\Tests\TestCase;
use Vigihdev\WpCliModels\Validators\Args\Menu\TermItemMenuArgsValidator;

/**
 * TermItemMenuArgsValidatorTest
 *
 * Unit test untuk TermItemMenuArgsValidator
 */
final class TermItemMenuArgsValidatorTest extends TestCase
{
    private TermItemMenuArgsValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TermItemMenuArgsValidator();
    }

    #[Test]
    public function it_throws_exception_when_invalid_dto_is_passed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Expected %s, got stdClass', TermItemMenuArgsDto::class));

        $this->validator->validate(new \stdClass());
    }

    #[Test]
    public function it_throws_exception_when_menu_is_empty(): void
    {
        $dto = new TermItemMenuArgsDto(menu: '', taxonomy: 'category', termId: 1);

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_taxonomy_is_empty(): void
    {
        $dto = new TermItemMenuArgsDto(menu: 'primary', taxonomy: '', termId: 1);

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_term_id_is_zero_or_negative(): void
    {
        $dto = new TermItemMenuArgsDto(menu: 'primary', taxonomy: 'category', termId: 0);

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_passes_validation_with_valid_data(): void
    {
        $dto = new TermItemMenuArgsDto(menu: 'primary', taxonomy: 'category', termId: 1);

        $this->validator->validate($dto);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_validates_single_field_menu(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu identifier is required.');

        TermItemMenuArgsValidator::validateField('menu', '');
    }

    #[Test]
    public function it_validates_single_field_taxonomy(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Taxonomy is required.');

        TermItemMenuArgsValidator::validateField('taxonomy', '');
    }

    #[Test]
    public function it_validates_single_field_term_id(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Term ID must be a positive integer.');

        TermItemMenuArgsValidator::validateField('termId', 0);
    }

    #[Test]
    public function it_passes_single_field_validation(): void
    {
        TermItemMenuArgsValidator::validateField('menu', 'primary');
        TermItemMenuArgsValidator::validateField('taxonomy', 'category');
        TermItemMenuArgsValidator::validateField('termId', 1);

        $this->addToAssertionCount(1);
    }
}
