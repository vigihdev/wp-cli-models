<?php

namespace Vigihdev\WpCliModels\Tests\Units\Validators;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Menu\PostItemMenuArgsDto;
use Vigihdev\WpCliModels\Exceptions\ValidationException;
use Vigihdev\WpCliModels\Tests\TestCase;
use Vigihdev\WpCliModels\Validators\Args\Menu\PostItemMenuArgsValidator;

/**
 * PostItemMenuArgsValidatorTest
 *
 * Unit test untuk PostItemMenuArgsValidator
 */
final class PostItemMenuArgsValidatorTest extends TestCase
{
    private PostItemMenuArgsValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new PostItemMenuArgsValidator();
    }

    #[Test]
    public function it_throws_exception_when_invalid_dto_is_passed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Expected %s, got stdClass', PostItemMenuArgsDto::class));

        $this->validator->validate(new \stdClass());
    }

    #[Test]
    public function it_throws_exception_when_menu_is_empty(): void
    {
        $dto = new PostItemMenuArgsDto(menu: '', postId: 1);

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_throws_exception_when_post_id_is_zero_or_negative(): void
    {
        $dto = new PostItemMenuArgsDto(menu: 'primary', postId: 0);

        $this->expectException(ValidationException::class);
        $this->validator->validate($dto);
    }

    #[Test]
    public function it_passes_validation_with_valid_data(): void
    {
        $dto = new PostItemMenuArgsDto(menu: 'primary', postId: 1);

        $this->validator->validate($dto);
        $this->addToAssertionCount(1);
    }

    #[Test]
    public function it_validates_single_field_menu(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Menu identifier is required.');

        PostItemMenuArgsValidator::validateField('menu', '');
    }

    #[Test]
    public function it_validates_single_field_post_id(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Post ID must be a positive integer.');

        PostItemMenuArgsValidator::validateField('postId', 0);
    }

    #[Test]
    public function it_passes_single_field_validation(): void
    {
        PostItemMenuArgsValidator::validateField('menu', 'primary');
        PostItemMenuArgsValidator::validateField('postId', 1);

        $this->addToAssertionCount(1);
    }
}
