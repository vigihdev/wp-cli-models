<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Tests\DTOs\Args\Post;

use PHPUnit\Framework\TestCase;
use Vigihdev\WpCliModels\DTOs\Args\Post\CreatePostArgsDto;
use InvalidArgumentException;

/**
 * Class CreatePostArgsDtoTest
 *
 * Test suite untuk CreatePostArgsDto
 */
final class CreatePostArgsDtoTest extends TestCase
{
    /**
     * Test membuat instance CreatePostArgsDto dengan parameter minimum
     *
     * @return void
     */
    public function testCanCreateWithRequiredParameters(): void
    {
        $dto = new CreatePostArgsDto(
            title: 'Test Post',
            content: 'Test Content'
        );

        $this->assertEquals('Test Post', $dto->getTitle());
        $this->assertEquals('Test Content', $dto->getContent());
        $this->assertNull($dto->getAuthor());
        $this->assertNull($dto->getDate());
    }

    /**
     * Test membuat instance CreatePostArgsDto dengan semua parameter
     *
     * @return void
     */
    public function testCanCreateWithAllParameters(): void
    {
        $dto = new CreatePostArgsDto(
            title: 'Test Post',
            content: 'Test Content',
            author: 1,
            date: '2023-01-01 12:00:00',
            dateGmt: '2023-01-01 10:00:00',
            contentFiltered: 'Filtered Content',
            excerpt: 'Test Excerpt',
            status: 'publish',
            type: 'post',
            commentStatus: 'open',
            pingStatus: 'open',
            password: 'secret',
            name: 'test-post',
            toPing: 'http://example.com',
            pinged: 'http://example.com/pinged',
            modified: '2023-01-01 13:00:00',
            modifiedGmt: '2023-01-01 11:00:00',
            parent: 10,
            menuOrder: 1,
            mimeType: 'text/html',
            guid: 'http://example.com/?p=1',
            category: [1, 2, 3],
            tagsInput: ['tag1', 'tag2'],
            taxInput: ['custom_tax' => ['term1', 'term2']],
            metaInput: ['key' => 'value']
        );

        $this->assertEquals(1, $dto->getAuthor());
        $this->assertEquals('2023-01-01 12:00:00', $dto->getDate());
        $this->assertEquals('2023-01-01 10:00:00', $dto->getDateGmt());
        $this->assertEquals('Filtered Content', $dto->getContentFiltered());
        $this->assertEquals('Test Post', $dto->getTitle());
        $this->assertEquals('Test Excerpt', $dto->getExcerpt());
        $this->assertEquals('publish', $dto->getStatus());
        $this->assertEquals('post', $dto->getType());
        $this->assertEquals('open', $dto->getCommentStatus());
        $this->assertEquals('open', $dto->getPingStatus());
        $this->assertEquals('secret', $dto->getPassword());
        $this->assertEquals('test-post', $dto->getName());
        $this->assertEquals('http://example.com', $dto->getToPing());
        $this->assertEquals('http://example.com/pinged', $dto->getPinged());
        $this->assertEquals('2023-01-01 13:00:00', $dto->getModified());
        $this->assertEquals('2023-01-01 11:00:00', $dto->getModifiedGmt());
        $this->assertEquals(10, $dto->getParent());
        $this->assertEquals(1, $dto->getMenuOrder());
        $this->assertEquals('text/html', $dto->getMimeType());
        $this->assertEquals('http://example.com/?p=1', $dto->getGuid());
        $this->assertEquals([1, 2, 3], $dto->getCategory());
        $this->assertEquals(['tag1', 'tag2'], $dto->getTagsInput());
        $this->assertEquals(['custom_tax' => ['term1', 'term2']], $dto->getTaxInput());
        $this->assertEquals(['key' => 'value'], $dto->getMetaInput());
    }

    /**
     * Test membuat instance melalui fromArray dengan data valid
     *
     * @return void
     */
    public function testCanCreateFromArray(): void
    {
        $data = [
            'title' => 'Test Post',
            'content' => 'Test Content',
            'author' => 1,
            'status' => 'publish'
        ];

        $dto = CreatePostArgsDto::fromArray($data);

        $this->assertEquals('Test Post', $dto->getTitle());
        $this->assertEquals('Test Content', $dto->getContent());
        $this->assertEquals(1, $dto->getAuthor());
        $this->assertEquals('publish', $dto->getStatus());
    }

    /**
     * Test exception saat membuat instance dari array tanpa title
     *
     * @return void
     */
    public function testThrowsExceptionWhenTitleMissingInFromArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title or content not empty');

        CreatePostArgsDto::fromArray([
            'content' => 'Test Content'
        ]);
    }

    /**
     * Test exception saat membuat instance dari array tanpa content
     *
     * @return void
     */
    public function testThrowsExceptionWhenContentMissingInFromArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title or content not empty');

        CreatePostArgsDto::fromArray([
            'title' => 'Test Post'
        ]);
    }

    /**
     * Test konversi objek ke array
     *
     * @return void
     */
    public function testCanBeConvertedToArray(): void
    {
        $dto = new CreatePostArgsDto(
            title: 'Test Post',
            content: 'Test Content',
            author: 1,
            status: 'publish'
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Test Post', $array['post_title']);
        $this->assertEquals('Test Content', $array['post_content']);
        $this->assertEquals(1, $array['post_author']);
        $this->assertEquals('publish', $array['post_status']);
    }

    /**
     * Test bahwa parameter null tidak dimasukkan dalam array
     *
     * @return void
     */
    public function testNullValuesAreFilteredOutInToArray(): void
    {
        $dto = new CreatePostArgsDto(
            title: 'Test Post',
            content: 'Test Content'
        );

        $array = $dto->toArray();

        // Null values should not be included
        $this->assertArrayNotHasKey('post_author', $array);
        // $this->assertArrayNotHasKey('post_excerpt', $array);
    }
}
