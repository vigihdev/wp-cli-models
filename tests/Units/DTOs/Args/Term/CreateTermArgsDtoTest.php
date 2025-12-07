<?php

namespace Vigihdev\WpCliModels\Tests\Units\DTOs\Args\Term;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\WpCliModels\DTOs\Args\Term\CreateTermArgsDto;
use Vigihdev\WpCliModels\Tests\TestCase;

/**
 * CreateTermArgsDtoTest
 *
 * Unit test untuk CreateTermArgsDto
 */
final class CreateTermArgsDtoTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_required_parameters(): void
    {
        $dto = new CreateTermArgsDto(
            taxonomy: 'category',
            term: 'News'
        );

        $this->assertInstanceOf(CreateTermArgsDto::class, $dto);
        $this->assertEquals('category', $dto->getTaxonomy());
        $this->assertEquals('News', $dto->getTerm());
        $this->assertNull($dto->getSlug());
        $this->assertNull($dto->getDescription());
        $this->assertNull($dto->getParent());
    }

    #[Test]
    public function it_can_be_instantiated_with_all_parameters(): void
    {
        $dto = new CreateTermArgsDto(
            taxonomy: 'category',
            term: 'Technology News',
            slug: 'tech-news',
            description: 'All about technology news',
            parent: 123
        );

        $this->assertInstanceOf(CreateTermArgsDto::class, $dto);
        $this->assertEquals('category', $dto->getTaxonomy());
        $this->assertEquals('Technology News', $dto->getTerm());
        $this->assertEquals('tech-news', $dto->getSlug());
        $this->assertEquals('All about technology news', $dto->getDescription());
        $this->assertEquals(123, $dto->getParent());
    }

    #[Test]
    public function it_can_be_created_from_array(): void
    {
        $data = [
            'taxonomy' => 'category',
            'term' => 'Technology News',
            'slug' => 'tech-news',
            'description' => 'All about technology news',
            'parent' => 123
        ];

        $dto = CreateTermArgsDto::fromArray($data);

        $this->assertInstanceOf(CreateTermArgsDto::class, $dto);
        $this->assertEquals('category', $dto->getTaxonomy());
        $this->assertEquals('Technology News', $dto->getTerm());
        $this->assertEquals('tech-news', $dto->getSlug());
        $this->assertEquals('All about technology news', $dto->getDescription());
        $this->assertEquals(123, $dto->getParent());
    }

    #[Test]
    public function it_throws_exception_when_taxonomy_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Taxonomy is required');

        CreateTermArgsDto::fromArray([
            'term' => 'News'
        ]);
    }

    #[Test]
    public function it_throws_exception_when_term_is_missing_in_from_array(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Term name is required');

        CreateTermArgsDto::fromArray([
            'taxonomy' => 'category'
        ]);
    }

    #[Test]
    public function it_throws_exception_when_parent_is_not_numeric(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parent must be a number');

        $data = [
            'taxonomy' => 'category',
            'term' => 'News',
            'parent' => 'not-a-number'
        ];

        CreateTermArgsDto::fromArray($data);
    }

    #[Test]
    public function it_returns_null_for_optional_fields_when_not_set(): void
    {
        $dto = new CreateTermArgsDto(
            taxonomy: 'category',
            term: 'News'
        );

        $this->assertNull($dto->getSlug());
        $this->assertNull($dto->getDescription());
        $this->assertNull($dto->getParent());
    }

    #[Test]
    public function it_converts_to_array_correctly(): void
    {
        $dto = new CreateTermArgsDto(
            taxonomy: 'category',
            term: 'Technology News',
            slug: 'tech-news',
            description: 'All about technology news',
            parent: 123
        );

        $array = $dto->toArray();

        $expected = [
            'slug' => 'tech-news',
            'description' => 'All about technology news',
            'parent' => 123
        ];

        $this->assertEquals($expected, $array);
    }

    #[Test]
    public function it_converts_to_array_without_null_values(): void
    {
        $dto = new CreateTermArgsDto(
            taxonomy: 'category',
            term: 'News'
            // Other optional fields are null
        );

        $array = $dto->toArray();

        $expected = [];

        $this->assertEquals($expected, $array);
    }
}
