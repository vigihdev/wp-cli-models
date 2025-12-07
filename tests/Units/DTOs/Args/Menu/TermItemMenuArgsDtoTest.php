<?php

namespace Vigihdev\WpCliModels\Tests\Units\DTOs\Args\Menu;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Menu\TermItemMenuArgsDto;
use Vigihdev\WpCliModels\Tests\TestCase;

/**
 * TermItemMenuArgsDtoTest
 *
 * Unit test untuk TermItemMenuArgsDto
 */
final class TermItemMenuArgsDtoTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_all_parameters(): void
    {
        $dto = new TermItemMenuArgsDto(
            menu: 'main-menu',
            taxonomy: 'category',
            termId: 456,
            title: 'News',
            link: 'https://example.com/category/news',
            description: 'News category',
            attrTitle: 'Go to news category',
            target: '_blank',
            classes: 'category-class',
            position: 2,
            parentId: 20
        );

        $this->assertInstanceOf(TermItemMenuArgsDto::class, $dto);
        $this->assertEquals('main-menu', $dto->getMenu());
        $this->assertEquals('category', $dto->getTaxonomy());
        $this->assertEquals(456, $dto->getTermId());
        $this->assertEquals('News', $dto->getTitle());
        $this->assertEquals('https://example.com/category/news', $dto->getLink());
        $this->assertEquals('News category', $dto->getDescription());
        $this->assertEquals('Go to news category', $dto->getAttrTitle());
        $this->assertEquals('_blank', $dto->getTarget());
        $this->assertEquals('category-class', $dto->getClasses());
        $this->assertEquals(2, $dto->getPosition());
        $this->assertEquals(20, $dto->getParentId());
    }

    #[Test]
    public function it_can_be_created_from_array(): void
    {
        $data = [
            'menu' => 'main-menu',
            'taxonomy' => 'category',
            'term_id' => 456,
            'title' => 'News',
            'link' => 'https://example.com/category/news',
            'description' => 'News category',
            'attr_title' => 'Go to news category',
            'target' => '_blank',
            'classes' => 'category-class',
            'position' => 2,
            'parent_id' => 20
        ];

        $dto = TermItemMenuArgsDto::fromArray($data);

        $this->assertInstanceOf(TermItemMenuArgsDto::class, $dto);
        $this->assertEquals('main-menu', $dto->getMenu());
        $this->assertEquals('category', $dto->getTaxonomy());
        $this->assertEquals(456, $dto->getTermId());
        $this->assertEquals('News', $dto->getTitle());
        $this->assertEquals('https://example.com/category/news', $dto->getLink());
        $this->assertEquals('News category', $dto->getDescription());
        $this->assertEquals('Go to news category', $dto->getAttrTitle());
        $this->assertEquals('_blank', $dto->getTarget());
        $this->assertEquals('category-class', $dto->getClasses());
        $this->assertEquals(2, $dto->getPosition());
        $this->assertEquals(20, $dto->getParentId());
    }

    #[Test]
    public function it_throws_exception_when_menu_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Menu identifier is required');

        TermItemMenuArgsDto::fromArray([
            'taxonomy' => 'category',
            'term_id' => 456
        ]);
    }

    #[Test]
    public function it_throws_exception_when_taxonomy_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Taxonomy is required');

        TermItemMenuArgsDto::fromArray([
            'menu' => 'main-menu',
            'term_id' => 456
        ]);
    }

    #[Test]
    public function it_throws_exception_when_term_id_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Valid term ID is required');

        TermItemMenuArgsDto::fromArray([
            'menu' => 'main-menu',
            'taxonomy' => 'category'
        ]);
    }

    #[Test]
    public function it_throws_exception_when_term_id_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Valid term ID is required');

        $data = [
            'menu' => 'main-menu',
            'taxonomy' => 'category',
            'term_id' => 'not-a-number'
        ];

        TermItemMenuArgsDto::fromArray($data);
    }

    #[Test]
    public function it_throws_exception_when_position_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Position must be a number');

        $data = [
            'menu' => 'main-menu',
            'taxonomy' => 'category',
            'term_id' => 456,
            'position' => 'not-a-number'
        ];

        TermItemMenuArgsDto::fromArray($data);
    }

    #[Test]
    public function it_throws_exception_when_parent_id_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parent ID must be a number');

        $data = [
            'menu' => 'main-menu',
            'taxonomy' => 'category',
            'term_id' => 456,
            'parent_id' => 'not-a-number'
        ];

        TermItemMenuArgsDto::fromArray($data);
    }

    #[Test]
    public function it_returns_null_for_optional_fields_when_not_set(): void
    {
        $dto = new TermItemMenuArgsDto(
            menu: 'main-menu',
            taxonomy: 'category',
            termId: 456
        );

        $this->assertNull($dto->getTitle());
        $this->assertNull($dto->getLink());
        $this->assertNull($dto->getDescription());
        $this->assertNull($dto->getAttrTitle());
        $this->assertNull($dto->getTarget());
        $this->assertNull($dto->getClasses());
        $this->assertNull($dto->getPosition());
        $this->assertNull($dto->getParentId());
    }

    #[Test]
    public function it_converts_to_array_correctly(): void
    {
        $dto = new TermItemMenuArgsDto(
            menu: 'main-menu',
            taxonomy: 'category',
            termId: 456,
            title: 'News',
            link: 'https://example.com/category/news',
            description: 'News category',
            attrTitle: 'Go to news category',
            target: '_blank',
            classes: 'category-class',
            position: 2,
            parentId: 20
        );

        $array = $dto->toArray();

        $expected = [
            'title' => 'News',
            'link' => 'https://example.com/category/news',
            'description' => 'News category',
            'attr-title' => 'Go to news category',
            'target' => '_blank',
            'classes' => 'category-class',
            'position' => 2,
            'parent-id' => 20
        ];

        $this->assertEquals($expected, $array);
    }

    #[Test]
    public function it_converts_to_array_without_null_values(): void
    {
        $dto = new TermItemMenuArgsDto(
            menu: 'main-menu',
            taxonomy: 'category',
            termId: 456,
            title: 'News',
            description: 'News category'
            // Other optional fields are null
        );

        $array = $dto->toArray();

        $expected = [
            'title' => 'News',
            'description' => 'News category'
        ];

        $this->assertEquals($expected, $array);
    }
}