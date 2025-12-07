<?php

namespace Vigihdev\WpCliModels\Tests\Units\DTOs\Args\Menu;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Menu\MenuArgsDto;
use Vigihdev\WpCliModels\Tests\TestCase;

/**
 * MenuArgsDtoTest
 *
 * Unit test untuk MenuArgsDto
 */
final class MenuArgsDtoTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_all_parameters(): void
    {
        $dto = new MenuArgsDto(
            name: 'Main Menu',
            slug: 'main-menu',
            description: 'Main navigation menu',
            location: 'primary'
        );

        $this->assertInstanceOf(MenuArgsDto::class, $dto);
        $this->assertEquals('Main Menu', $dto->getName());
        $this->assertEquals('main-menu', $dto->getSlug());
        $this->assertEquals('Main navigation menu', $dto->getDescription());
        $this->assertEquals('primary', $dto->getLocation());
    }

    #[Test]
    public function it_can_be_created_from_array(): void
    {
        $data = [
            'name' => 'Footer Menu',
            'slug' => 'footer-menu',
            'description' => 'Footer navigation',
            'location' => 'footer'
        ];

        $dto = MenuArgsDto::fromArray($data);

        $this->assertInstanceOf(MenuArgsDto::class, $dto);
        $this->assertEquals('Footer Menu', $dto->getName());
        $this->assertEquals('footer-menu', $dto->getSlug());
        $this->assertEquals('Footer navigation', $dto->getDescription());
        $this->assertEquals('footer', $dto->getLocation());
    }

    #[Test]
    public function it_throws_exception_when_name_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Name is required to create MenuArgsDto');

        MenuArgsDto::fromArray([
            'slug' => 'footer-menu',
            'description' => 'Footer navigation',
            'location' => 'footer'
        ]);
    }

    #[Test]
    public function it_generates_slug_from_name_when_not_provided(): void
    {
        $dto = new MenuArgsDto(name: 'Main Navigation Menu');

        $this->assertEquals('main-navigation-menu', $dto->getSlug());
    }

    #[Test]
    public function it_returns_null_for_optional_fields_when_not_set(): void
    {
        $dto = new MenuArgsDto(name: 'Simple Menu');

        $this->assertNull($dto->getDescription());
        $this->assertNull($dto->getLocation());
    }

    #[Test]
    public function it_converts_to_array_correctly(): void
    {
        $dto = new MenuArgsDto(
            name: 'Header Menu',
            slug: 'header-menu',
            description: 'Header navigation',
            location: 'header'
        );

        $array = $dto->toArray();

        $expected = [
            'menu-name' => 'Header Menu',
            'menu-slug' => 'header-menu',
            'menu-description' => 'Header navigation',
            'menu-location' => 'header',
        ];

        $this->assertEquals($expected, $array);
    }

    #[Test]
    public function it_converts_to_array_with_generated_slug(): void
    {
        $dto = new MenuArgsDto(name: 'Test Menu');

        $array = $dto->toArray();

        $expected = [
            'menu-name' => 'Test Menu',
            'menu-slug' => 'test-menu',
            'menu-description' => null,
            'menu-location' => null,
        ];

        $this->assertEquals($expected, $array);
    }
}
