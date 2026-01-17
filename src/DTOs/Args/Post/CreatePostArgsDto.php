<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Args\Post;

use DateTime;
use InvalidArgumentException;
use Vigihdev\WpCliModels\Contracts\Args\Post\CreatePostArgsInterface;
use Vigihdev\WpCliModels\DTOs\Args\BaseArgsDto;

/**
 * Class CreatePostArgsDto
 *
 * DTO untuk menyimpan dan mengakses data pembuatan post di WordPress
 */
final class CreatePostArgsDto extends BaseArgsDto implements CreatePostArgsInterface
{
    /**
     * Membuat instance objek CreatePostArgsDto dengan parameter yang ditentukan
     *
     * @param int|null $author ID user yang membuat post
     * @param string|null $date Tanggal pembuatan post
     * @param string|null $dateGmt Tanggal pembuatan post dalam GMT
     * @param string|null $content Konten post
     * @param string|null $contentFiltered Konten post yang telah difilter
     * @param string|null $title Judul post
     * @param string|null $excerpt Kutipan post
     * @param string|null $status Status post
     * @param string|null $type Tipe post
     * @param string|null $commentStatus Status komentar post
     * @param string|null $pingStatus Status ping post
     * @param string|null $password Password post
     * @param string|null $name Nama post (slug)
     * @param string|null $toPing Daftar URL untuk diping
     * @param string|null $pinged Daftar URL yang sudah diping
     * @param string|null $modified Tanggal modifikasi post
     * @param string|null $modifiedGmt Tanggal modifikasi post dalam GMT
     * @param int|null $parent ID post parent
     * @param int|null $menuOrder Urutan menu post
     * @param string|null $mimeType Tipe MIME post
     * @param string|null $guid GUID post
     * @param array|null $category Array kategori post
     * @param array|null $tagsInput Array tag post
     * @param array|null $taxInput Array input taksonomi
     * @param array|null $metaInput Array input meta
     */
    public function __construct(
        private readonly string $title,
        private readonly string $content,
        private readonly ?int $author = null,
        private readonly ?string $date = null,
        private readonly ?string $dateGmt = null,
        private readonly ?string $contentFiltered = null,
        private readonly ?string $excerpt = null,
        private readonly ?string $status = null,
        private readonly ?string $type = null,
        private readonly ?string $commentStatus = null,
        private readonly ?string $pingStatus = null,
        private readonly ?string $password = null,
        private readonly ?string $name = null,
        private readonly ?string $toPing = null,
        private readonly ?string $pinged = null,
        private readonly ?string $modified = null,
        private readonly ?string $modifiedGmt = null,
        private readonly ?int $parent = null,
        private readonly ?int $menuOrder = null,
        private readonly ?string $mimeType = null,
        private readonly ?string $guid = null,
        private readonly ?array $category = null,
        private readonly ?array $tagsInput = null,
        private readonly ?array $taxInput = null,
        private readonly ?array $metaInput = null,
    ) {}

    /**
     * Mendapatkan ID author dari post
     *
     * @return int|null ID user yang membuat post
     */
    public function getAuthor(): ?int
    {
        return $this->author;
    }

    /**
     * Mendapatkan tanggal pembuatan post
     *
     * @return string|null Tanggal pembuatan post
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * Mendapatkan tanggal pembuatan post dalam zona waktu GMT
     *
     * @return string|null Tanggal pembuatan post dalam GMT
     */
    public function getDateGmt(): ?string
    {
        return $this->dateGmt;
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
     * Mendapatkan konten post yang telah difilter
     *
     * @return string|null Konten post yang telah difilter
     */
    public function getContentFiltered(): ?string
    {
        return $this->contentFiltered;
    }

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
     * Mendapatkan kutipan dari post
     *
     * @return string|null Kutipan post
     */
    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    /**
     * Mendapatkan status dari post
     *
     * @return string|null Status post
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Mendapatkan tipe dari post
     *
     * @return string|null Tipe post
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Mendapatkan status komentar untuk post
     *
     * @return string|null Status komentar post
     */
    public function getCommentStatus(): ?string
    {
        return $this->commentStatus;
    }

    /**
     * Mendapatkan status ping untuk post
     *
     * @return string|null Status ping post
     */
    public function getPingStatus(): ?string
    {
        return $this->pingStatus;
    }

    /**
     * Mendapatkan password dari post
     *
     * @return string|null Password post
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Mendapatkan slug nama post
     *
     * @return string|null Nama post (slug)
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Mendapatkan daftar URL yang akan diping
     *
     * @return string|null Daftar URL untuk diping
     */
    public function getToPing(): ?string
    {
        return $this->toPing;
    }

    /**
     * Mendapatkan daftar URL yang sudah diping
     *
     * @return string|null Daftar URL yang sudah diping
     */
    public function getPinged(): ?string
    {
        return $this->pinged;
    }

    /**
     * Mendapatkan tanggal modifikasi post
     *
     * @return string|null Tanggal modifikasi post
     */
    public function getModified(): ?string
    {
        return $this->modified;
    }

    /**
     * Mendapatkan tanggal modifikasi post dalam zona waktu GMT
     *
     * @return string|null Tanggal modifikasi post dalam GMT
     */
    public function getModifiedGmt(): ?string
    {
        return $this->modifiedGmt;
    }

    /**
     * Mendapatkan ID parent dari post
     *
     * @return int|null ID post parent
     */
    public function getParent(): ?int
    {
        return $this->parent;
    }

    /**
     * Mendapatkan urutan menu dari post
     *
     * @return int|null Urutan menu post
     */
    public function getMenuOrder(): ?int
    {
        return $this->menuOrder;
    }

    /**
     * Mendapatkan tipe MIME dari post
     *
     * @return string|null Tipe MIME post
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * Mendapatkan GUID dari post
     *
     * @return string|null GUID post
     */
    public function getGuid(): ?string
    {
        return $this->guid;
    }

    /**
     * Mendapatkan kategori post
     *
     * @return array|null Array kategori post
     */
    public function getCategory(): ?array
    {
        return $this->category;
    }

    /**
     * Mendapatkan tag input untuk post
     *
     * @return array|null Array tag post
     */
    public function getTagsInput(): ?array
    {
        return $this->tagsInput;
    }

    /**
     * Mendapatkan input taksonomi untuk post
     *
     * @return array|null Array input taksonomi
     */
    public function getTaxInput(): ?array
    {
        return $this->taxInput;
    }

    /**
     * Mendapatkan input meta untuk post
     *
     * @return array|null Array input meta
     */
    public function getMetaInput(): ?array
    {
        return $this->metaInput;
    }

    /**
     * Membuat instance CreatePostArgsDto dari array data
     *
     * @param array $data Array data untuk membuat objek CreatePostArgsDto
     * @return static Instance baru dari CreatePostArgsDto
     */
    public static function fromArray(array $data): static
    {

        if (!isset($data['title']) || !isset($data['content'])) {
            throw new InvalidArgumentException(
                sprintf('%s', 'Title or content not empty')
            );
        }

        return new static(
            title: $data['title'],
            content: $data['content'],
            author: (int)$data['author'] ?? null,
            date: $data['date'] ?? null,
            dateGmt: $data['dateGmt'] ?? null,
            contentFiltered: $data['contentFiltered'] ?? null,
            excerpt: $data['excerpt'] ?? null,
            status: $data['status'] ?? null,
            type: $data['type'] ?? null,
            commentStatus: $data['commentStatus'] ?? null,
            pingStatus: $data['pingStatus'] ?? null,
            password: $data['password'] ?? null,
            name: $data['name'] ?? null,
            toPing: $data['toPing'] ?? null,
            pinged: $data['pinged'] ?? null,
            modified: $data['modified'] ?? null,
            modifiedGmt: $data['modifiedGmt'] ?? null,
            parent: $data['parent'] ?? null,
            menuOrder: $data['menuOrder'] ?? null,
            mimeType: $data['mimeType'] ?? null,
            guid: $data['guid'] ?? null,
            category: $data['category'] ?? null,
            tagsInput: $data['tagsInput'] ?? null,
            taxInput: $data['taxInput'] ?? null,
            metaInput: $data['metaInput'] ?? null,
        );
    }

    private function loadDefaultValue(): array
    {
        $postDefault = [
            'post_title'   => sanitize_text_field($this->title),
            'post_content' => wp_kses_post($this->content ?? ''),
            'post_status'  => $this->status ?? 'draft',
            'post_type'    => $this->type ?? 'post',
            // 'post_author'  => self::validateAuthor($args['author'] ?? $authorId),
            'post_excerpt' => wp_kses_post($this->excerpt ?? ''),
            'post_date'    => get_date_from_gmt(
                $this->date ?? (new DateTime('now'))->format(DATE_W3C)
            ),
            'post_date_gmt'    => get_gmt_from_date(
                $this->date ?? (new DateTime('now'))->format(DATE_W3C)
            ),
            'post_modified' => get_date_from_gmt(
                $this->modified ?? (new DateTime('now'))->format(DATE_W3C)
            ),
            'post_modified_gmt' => get_gmt_from_date(
                $this->modifiedGmt ?? (new DateTime('now'))->format(DATE_W3C)
            ),
            'post_name'    => sanitize_title($this->name ?? $this->title),
            'post_parent'  => absint($this->parent ?? 0),
            'comment_status' => $this->commentStatus ?? get_option('default_comment_status', 'open'),
            'ping_status'    => $this->pingStatus ?? get_option('default_ping_status', 'open'),
        ];

        return $postDefault;
    }

    /**
     * Mengkonversi objek CreatePostArgsDto menjadi array yang sesuai dengan parameter WP_Post
     *
     * @return array Array data untuk pembuatan post di WordPress
     */
    public function toArray(): array
    {

        $postData = array_filter([
            'post_title' => sanitize_text_field($this->title),
            'post_name' => $this->name,
            'post_status' => $this->status,
            'post_author' => $this->author,
            'post_type' => $this->type,
            'post_date' => $this->date,
            'post_date_gmt' => $this->dateGmt,
            'post_modified' => $this->modified,
            'post_modified_gmt' => $this->modifiedGmt,
            'post_content' => $this->content,
            'post_content_filtered' => $this->contentFiltered,
            'post_excerpt' => $this->excerpt,
            'comment_status' => $this->commentStatus,
            'ping_status' => $this->pingStatus,
            'post_password' => $this->password,
            'to_ping' => $this->toPing,
            'pinged' => $this->pinged,
            'post_parent' => $this->parent,
            'menu_order' => $this->menuOrder,
            'post_mime_type' => $this->mimeType,
            'guid' => $this->guid,
            'post_category' => $this->category,
            'tags_input' => $this->tagsInput,
            'tax_input' => $this->taxInput,
            'meta_input' => $this->metaInput,
        ], function ($value) {
            return $value !== null;
        });

        return array_merge($this->loadDefaultValue(), $postData);
    }
}
