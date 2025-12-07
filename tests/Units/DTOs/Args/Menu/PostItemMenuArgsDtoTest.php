<?php

namespace Vigihdev\WpCliModels\Tests\Units\DTOs\Args\Menu;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Menu\PostItemMenuArgsDto;
use Vigihdev\WpCliModels\Tests\TestCase;

/**
 * PostItemMenuArgsDtoTest
 *
 * Unit test untuk PostItemMenuArgsDto
 */
final class PostItemMenuArgsDtoTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_all_parameters(): void
    {
        $dto = new PostItemMenuArgsDto(
            menu: 'main-menu',
            postId: 123,
            title: 'Home',
            link: 'https://example.com',
            description: 'Homepage link',
            attrTitle: 'Go to homepage',
            target: '_blank',
            classes: 'custom-class',
            position: 1,
            parentId: 10,
            porcelain: true
        );

        $this->assertInstanceOf(PostItemMenuArgsDto::class, $dto);
        $this->assertEquals('main-menu', $dto->getMenu());
        $this->assertEquals(123, $dto->getPostId());
        $this->assertEquals('Home', $dto->getTitle());
        $this->assertEquals('https://example.com', $dto->getLink());
        $this->assertEquals('Homepage link', $dto->getDescription());
        $this->assertEquals('Go to homepage', $dto->getAttrTitle());
        $this->assertEquals('_blank', $dto->getTarget());
        $this->assertEquals('custom-class', $dto->getClasses());
        $this->assertEquals(1, $dto->getPosition());
        $this->assertEquals(10, $dto->getParentId());
        $this->assertTrue($dto->getPorcelain());
    }

    #[Test]
    public function it_can_be_created_from_array(): void
    {
        $data = [
            'menu' => 'main-menu',
            'post_id' => 123,
            'title' => 'Home',
            'link' => 'https://example.com',
            'description' => 'Homepage link',
            'attr_title' => 'Go to homepage',
            'target' => '_blank',
            'classes' => 'custom-class',
            'position' => 1,
            'parent_id' => 10,
            'porcelain' => true
        ];

        $dto = PostItemMenuArgsDto::fromArray($data);

        $this->assertInstanceOf(PostItemMenuArgsDto::class, $dto);
        $this->assertEquals('main-menu', $dto->getMenu());
        $this->assertEquals(123, $dto->getPostId());
        $this->assertEquals('Home', $dto->getTitle());
        $this->assertEquals('https://example.com', $dto->getLink());
        $this->assertEquals('Homepage link', $dto->getDescription());
        $this->assertEquals('Go to homepage', $dto->getAttrTitle());
        $this->assertEquals('_blank', $dto->getTarget());
        $this->assertEquals('custom-class', $dto->getClasses());
        $this->assertEquals(1, $dto->getPosition());
        $this->assertEquals(10, $dto->getParentId());
        $this->assertTrue($dto->getPorcelain());
    }

    #[Test]
    public function it_throws_exception_when_menu_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Menu identifier is required');

        PostItemMenuArgsDto::fromArray([
            'post_id' => 123,
            'title' => 'Home'
        ]);
    }

    #[Test]
    public function it_throws_exception_when_post_id_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Valid post ID is required');

        PostItemMenuArgsDto::fromArray([
            'menu' => 'main-menu',
            'title' => 'Home'
        ]);
    }

    #[Test]
    public function it_throws_exception_when_post_id_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Valid post ID is required');

        $data = [
            'menu' => 'main-menu',
            'post_id' => 'not-a-number'
        ];

        PostItemMenuArgsDto::fromArray($data);
    }

    #[Test]
    public function it_throws_exception_when_position_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Position must be a number');

        $data = [
            'menu' => 'main-menu',
            'post_id' => 123,
            'position' => 'not-a-number'
        ];

        PostItemMenuArgsDto::fromArray($data);
    }

    #[Test]
    public function it_throws_exception_when_parent_id_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parent ID must be a number');

        $data = [
            'menu' => 'main-menu',
            'post_id' => 123,
            'parent_id' => 'not-a-number'
        ];

        PostItemMenuArgsDto::fromArray($data);
    }

    #[Test]
    public function it_returns_null_for_optional_fields_when_not_set(): void
    {
        $dto = new PostItemMenuArgsDto(
            menu: 'main-menu',
            postId: 123
        );

        $this->assertNull($dto->getTitle());
        $this->assertNull($dto->getLink());
        $this->assertNull($dto->getDescription());
        $this->assertNull($dto->getAttrTitle());
        $this->assertNull($dto->getTarget());
        $this->assertNull($dto->getClasses());
        $this->assertNull($dto->getPosition());
        $this->assertNull($dto->getParentId());
        $this->assertFalse($dto->getPorcelain());
    }

    #[Test]
    public function it_converts_to_array_correctly(): void
    {
        $dto = new PostItemMenuArgsDto(
            menu: 'main-menu',
            postId: 123,
            title: 'Home',
            link: 'https://example.com',
            description: 'Homepage link',
            attrTitle: 'Go to homepage',
            target: '_blank',
            classes: 'custom-class',
            position: 1,
            parentId: 10,
            porcelain: true
        );

        $array = $dto->toArray();

        $expected = [
            'title' => 'Home',
            'link' => 'https://example.com',
            'description' => 'Homepage link',
            'attr-title' => 'Go to homepage',
            'target' => '_blank',
            'classes' => 'custom-class',
            'position' => 1,
            'parent-id' => 10,
            'porcelain' => true
        ];

        $this->assertEquals($expected, $array);
    }

    #[Test]
    public function it_converts_to_array_without_null_values(): void
    {
        $dto = new PostItemMenuArgsDto(
            menu: 'main-menu',
            postId: 123,
            title: 'Home',
            description: 'Homepage link'
            // Other optional fields are null
        );

        $array = $dto->toArray();

        $expected = [
            'title' => 'Home',
            'description' => 'Homepage link',
            'porcelain' => false,

        ];

        $this->assertEquals($expected, $array);
    }
}
