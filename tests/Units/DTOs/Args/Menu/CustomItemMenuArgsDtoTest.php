<?php

namespace Vigihdev\WpCliModels\Tests\Units\DTOs\Args\Menu;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Menu\CustomItemMenuArgsDto;
use Vigihdev\WpCliModels\Tests\TestCase;

/**
 * CustomItemMenuArgsDtoTest
 *
 * Unit test untuk CustomItemMenuArgsDto
 */
final class CustomItemMenuArgsDtoTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_all_parameters(): void
    {
        $dto = new CustomItemMenuArgsDto(
            menu: 'main-menu',
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

        $this->assertInstanceOf(CustomItemMenuArgsDto::class, $dto);
        $this->assertEquals('main-menu', $dto->getMenu());
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

        $dto = CustomItemMenuArgsDto::fromArray($data);

        $this->assertInstanceOf(CustomItemMenuArgsDto::class, $dto);
        $this->assertEquals('main-menu', $dto->getMenu());
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

        CustomItemMenuArgsDto::fromArray([
            'title' => 'Home',
            'link' => 'https://example.com'
        ]);
    }

    #[Test]
    public function it_throws_exception_when_title_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Title is required');

        CustomItemMenuArgsDto::fromArray([
            'menu' => 'main-menu',
            'link' => 'https://example.com'
        ]);
    }

    #[Test]
    public function it_throws_exception_when_link_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Link URL is required');

        CustomItemMenuArgsDto::fromArray([
            'menu' => 'main-menu',
            'title' => 'Home'
        ]);
    }

    #[Test]
    public function it_throws_exception_when_position_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Position must be a number');

        $data = [
            'menu' => 'main-menu',
            'title' => 'Home',
            'link' => 'https://example.com',
            'position' => 'not-a-number'
        ];

        CustomItemMenuArgsDto::fromArray($data);
    }

    #[Test]
    public function it_throws_exception_when_parent_id_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parent ID must be a number');

        $data = [
            'menu' => 'main-menu',
            'title' => 'Home',
            'link' => 'https://example.com',
            'parent_id' => 'not-a-number'
        ];

        CustomItemMenuArgsDto::fromArray($data);
    }

    #[Test]
    public function it_returns_null_for_optional_fields_when_not_set(): void
    {
        $dto = new CustomItemMenuArgsDto(
            menu: 'main-menu',
            title: 'Home',
            link: 'https://example.com'
        );

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
        $dto = new CustomItemMenuArgsDto(
            menu: 'main-menu',
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
        $dto = new CustomItemMenuArgsDto(
            menu: 'main-menu',
            title: 'Home',
            link: 'https://example.com',
            description: 'Homepage link'
            // Other optional fields are null
        );

        $array = $dto->toArray();

        $expected = [
            'description' => 'Homepage link',
            'porcelain' => false,
        ];

        $this->assertEquals($expected, $array);
    }
}
