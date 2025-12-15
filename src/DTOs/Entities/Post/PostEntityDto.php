<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Entities\Post;

use InvalidArgumentException;
use Vigihdev\WpCliModels\Contracts\Entities\Post\PostEntityInterface;
use Vigihdev\WpCliModels\DTOs\Entities\Author\UserEntityDto;
use Vigihdev\WpCliModels\DTOs\Entities\BaseEntityDto;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\TermEntityDto;

final class PostEntityDto extends BaseEntityDto implements PostEntityInterface
{


    public function __construct(
        private readonly int $id,
        private readonly string $author = '0',
        private readonly string $date = '0000-00-00 00:00:00',
        private readonly string $dateGmt = '0000-00-00 00:00:00',
        private readonly string $content = '',
        private readonly string $title = '',
        private readonly string $excerpt = '',
        private readonly string $status = 'publish',
        private readonly string $commentStatus = 'open',
        private readonly string $pingStatus = 'open',
        private readonly string $password = '',
        private readonly string $name = '',
        private readonly string $toPing = '',
        private readonly string $pinged = '',
        private readonly string $modified = '0000-00-00 00:00:00',
        private readonly string $modifiedGmt = '0000-00-00 00:00:00',
        private readonly string $contentFiltered = '',
        private readonly int $parent = 0,
        private readonly string $guid = '',
        private readonly int $menuOrder = 0,
        private readonly string $type = 'post',
        private readonly string $mimeType = '',
        private readonly int $commentCount = 0,
        private readonly string $filter = ''
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getDateGmt(): string
    {
        return $this->dateGmt;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCommentStatus(): string
    {
        return $this->commentStatus;
    }

    public function getPingStatus(): string
    {
        return $this->pingStatus;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getToPing(): string
    {
        return $this->toPing;
    }

    public function getPinged(): string
    {
        return $this->pinged;
    }

    public function getModified(): string
    {
        return $this->modified;
    }

    public function getModifiedGmt(): string
    {
        return $this->modifiedGmt;
    }

    public function getContentFiltered(): string
    {
        return $this->contentFiltered;
    }

    public function getParent(): int
    {
        return $this->parent;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getMenuOrder(): int
    {
        return $this->menuOrder;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    public function getFilter(): string
    {
        return $this->filter;
    }

    public static function fromQuery(mixed $data): static
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('Data must be an array or object');
        }

        return static::fromArray($data);
    }

    public function toArray(): array
    {
        return [
            'ID' => $this->getId(),
            'post_author' => $this->getAuthor(),
            'post_date' => $this->getDate(),
            'post_date_gmt' => $this->getDateGmt(),
            'post_content' => $this->getContent(),
            'post_title' => $this->getTitle(),
            'post_excerpt' => $this->getExcerpt(),
            'post_status' => $this->getStatus(),
            'comment_status' => $this->getCommentStatus(),
            'ping_status' => $this->getPingStatus(),
            'post_password' => $this->getPassword(),
            'post_name' => $this->getName(),
            'to_ping' => $this->getToPing(),
            'pinged' => $this->getPinged(),
            'post_modified' => $this->getModified(),
            'post_modified_gmt' => $this->getModifiedGmt(),
            'post_content_filtered' => $this->getContentFiltered(),
            'post_parent' => $this->getParent(),
            'guid' => $this->getGuid(),
            'menu_order' => $this->getMenuOrder(),
            'post_type' => $this->getType(),
            'post_mime_type' => $this->getMimeType(),
            'comment_count' => $this->getCommentCount(),
            'filter' => $this->getFilter(),
        ];
    }

    public static function fromArray(array $data): static
    {
        if (!isset($data['ID'])) {
            throw new InvalidArgumentException('ID is required');
        }

        if (!isset($data['post_title'])) {
            throw new InvalidArgumentException('post_title is required');
        }

        return new self(
            id: (int) $data['ID'],
            author: $data['post_author'] ?? '',
            date: $data['post_date'] ?? '',
            dateGmt: $data['post_date_gmt'] ?? '',
            content: $data['post_content'] ?? '',
            title: $data['post_title'],
            excerpt: $data['post_excerpt'] ?? '',
            status: $data['post_status'] ?? '',
            commentStatus: $data['comment_status'] ?? '',
            pingStatus: $data['ping_status'] ?? '',
            password: $data['post_password'] ?? '',
            name: $data['post_name'] ?? '',
            toPing: $data['to_ping'] ?? '',
            pinged: $data['pinged'] ?? '',
            modified: $data['post_modified'] ?? '',
            modifiedGmt: $data['post_modified_gmt'] ?? '',
            contentFiltered: $data['post_content_filtered'] ?? '',
            parent: (int) ($data['post_parent'] ?? 0),
            guid: $data['guid'] ?? '',
            menuOrder: (int) ($data['menu_order'] ?? 0),
            type: $data['post_type'] ?? '',
            mimeType: $data['post_mime_type'] ?? '',
            commentCount: (int) ($data['comment_count'] ?? 0),
            filter: $data['filter'] ?? ''
        );
    }

    /**
     *
     * @return UserEntityDto|null
     */
    public function getAuthors(): ?UserEntityDto
    {
        $data = get_user($this->author)?->data;
        return $data ? UserEntityDto::fromQuery($data) : null;
    }

    /**
     *
     * @return TermEntityDto[]
     */
    public function getTerms(array $taxonomys = ['category']): array
    {

        $terms = get_terms([
            'taxonomy' => $taxonomys,
            'post_id' => $this->getId(),
        ]);

        $items = [];
        foreach ($terms as $term) {
            $items[] = TermEntityDto::fromQuery($term);
        }
        return $items;
    }
}
