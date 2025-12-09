<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Fields;

use Vigihdev\WpCliModels\Contracts\Fields\PostTypeFieldInterface;

/**
 * Class PostTypeFieldDto
 *
 * DTO untuk menyimpan dan mengakses data field penting dari post di WP-CLI
 */
final class PostTypeFieldDto extends BaseFieldDto implements PostTypeFieldInterface
{
    /**
     * Membuat instance objek PostFieldDto dengan parameter yang ditentukan
     *
     * @param string $title    Judul post
     * @param string $content  Konten post
     * @param string $status   Status post
     * @param string $type Tipe post
     * @param array $taxInput
     * @param int    $author ID author
     */
    public function __construct(
        private readonly string $title,
        private readonly string $content,
        private readonly string $status,
        private readonly string $type,
        private readonly int $author,
        private readonly array $taxInput,
    ) {}


    /**
     * Mendapatkan judul dari post
     *
     * @return string Judul post
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Mendapatkan konten dari post
     *
     * @return string Konten post
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Mendapatkan status dari post
     *
     * @return string Status post
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Mendapatkan tipe post
     *
     * @return string Tipe post
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Mendapatkan author ID dari post
     *
     * @return int ID author
     */
    public function getAuthor(): int
    {
        return $this->author;
    }

    /**
     * @return array<string,int[]>
     */
    public function getTaxInput(): array
    {
        return $this->taxInput;
    }

    /**
     *
     * @return TaxonomiInputDto[]
     */
    public function getTaxonomiInputs(): array
    {
        return array_map(fn($value) => new TaxonomiInputDto(taxInput: $value), $this->taxInput);
    }

    /**
     * Mengkonversi objek PostFieldDto menjadi array
     *
     * @return array<string, mixed> Array asosiatif yang berisi data post
     */
    public function toArray(): array
    {
        return array_filter([
            'post_title' => $this->getTitle(),
            'post_content' => $this->getContent(),
            'post_status' => $this->getStatus(),
            'post_type' => $this->getType(),
            'post_author' => $this->getAuthor(),
            'tag_input' => $this->getTaxInput(),
        ], function ($value) {
            return $value !== null;
        });
    }

    /**
     * Membuat instance PostFieldDto dari array data
     *
     * @param array<string, mixed> $data Data untuk membuat objek PostFieldDto
     *
     * @return self Instance baru dari PostFieldDto
     */
    public static function fromArray(array $data): static
    {
        return new self(
            title: (string) ($data['title'] ?? ''),
            content: (string) ($data['content'] ?? ''),
            status: (string) ($data['status'] ?? ''),
            type: (string) ($data['type'] ?? ''),
            author: (int) ($data['author'] ?? 0),
            taxInput: $data['taxInput'] ?? []
        );
    }
}
