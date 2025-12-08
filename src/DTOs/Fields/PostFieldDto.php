<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Fields;

use DateTime;
use Vigihdev\WpCliModels\Contracts\Fields\PostFieldInterface;

/**
 * Class PostFieldDto
 *
 * DTO untuk menyimpan dan mengakses data field penting dari post di WP-CLI
 */
final class PostFieldDto extends BaseFieldDto implements PostFieldInterface
{
    /**
     * Membuat instance objek PostFieldDto dengan parameter yang ditentukan
     *
     * @param string $title    Judul post
     * @param string $content  Konten post
     * @param string $status   Status post
     * @param string $type Tipe post
     * @param int    $author ID author
     */
    public function __construct(
        private readonly string $title,
        private readonly string $content,
        private readonly string $status,
        private readonly string $type,
        private readonly int $author,
        private readonly array $taxInput = [],
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

    public function getTaxInput(): array
    {
        return $this->taxInput;
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
            'tax_input' => $this->getTaxInput(),
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
            (string) ($data['title'] ?? ''),
            (string) ($data['content'] ?? ''),
            (string) ($data['status'] ?? ''),
            (string) ($data['type'] ?? ''),
            (int) ($data['author'] ?? 0),
        );
    }

    public function loadDefaultValues(): array
    {
        $postDefault = [
            'post_title'        => sanitize_text_field($this->title),
            'post_content'      => wp_kses_post($this->content ?? ''),
            'post_status'       => $this->status ?? 'draft',
            'post_type'         => $this->type ?? 'post',
            'post_excerpt'      => wp_kses_post($this->excerpt ?? ''),
            'post_date'         => get_date_from_gmt((new DateTime('now'))->format(DATE_W3C)),
            'post_date_gmt'     => get_gmt_from_date((new DateTime('now'))->format(DATE_W3C)),
            'post_modified'     => get_date_from_gmt((new DateTime('now'))->format(DATE_W3C)),
            'post_modified_gmt' => get_gmt_from_date((new DateTime('now'))->format(DATE_W3C)),
            'post_name'         => sanitize_title($this->title),
            'post_parent'       => 0,
            'comment_status'    => get_option('default_comment_status', 'open'),
            'ping_status'       => get_option('default_ping_status', 'open'),
        ];

        return $postDefault;
    }
}
