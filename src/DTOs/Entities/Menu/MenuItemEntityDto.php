<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Entities\Menu;

use Vigihdev\WpCliModels\DTOs\Entities\BaseEntityDto;
use InvalidArgumentException;
use Vigihdev\WpCliModels\Contracts\Entities\Menu\MenuItemEntityInterface;

/**
 * Class MenuItemEntityDto
 *
 * DTO untuk menyimpan dan mengakses data menu item
 */
final class MenuItemEntityDto extends BaseEntityDto implements MenuItemEntityInterface
{
    /**
     * Membuat instance objek MenuItemEntityDto dengan parameter yang ditentukan
     *
     * @param int $id ID menu item
     * @param string $author Author menu item
     * @param string $date Tanggal pembuatan menu item
     * @param string $dateGmt Tanggal pembuatan menu item dalam format GMT
     * @param string $content Konten menu item
     * @param string $title Judul menu item
     * @param string $excerpt Kutipan menu item
     * @param string $status Status menu item
     * @param string $commentStatus Status komentar menu item
     * @param string $pingStatus Status ping menu item
     * @param string $password Password menu item
     * @param string $name Nama/slug menu item
     * @param string $toPing To ping menu item
     * @param string $pinged Pinged menu item
     * @param string $modified Tanggal modifikasi menu item
     * @param string $modifiedGmt Tanggal modifikasi menu item dalam format GMT
     * @param string $contentFiltered Konten yang telah difilter
     * @param int $parent ID parent menu item
     * @param string $guid GUID menu item
     * @param int $menuOrder Urutan menu item
     * @param string $type Tipe post menu item
     * @param string $mimeType Mime type menu item
     * @param string $commentCount Jumlah komentar
     * @param string $filter Filter yang digunakan
     * @param int $dbId Database ID menu item
     * @param string $menuItemParent ID parent menu item
     * @param string $objectId ID objek yang direferensikan
     * @param string $object Jenis objek yang direferensikan
     * @param string $type Tipe menu item
     * @param string $typeLabel Label tipe menu item
     * @param string $title Judul menu item
     * @param string $url URL menu item
     * @param string $target Target link (_blank, _self, dll)
     * @param string $attrTitle Title atribut
     * @param string $description Deskripsi menu item
     * @param array $classes Kelas CSS untuk menu item
     * @param string $xfn Nilai XFN (XHTML Friends Network)
     */
    public function __construct(
        private readonly int $id,
        private readonly string $author,
        private readonly string $date,
        private readonly string $dateGmt,
        private readonly string $content,
        private readonly string $postTitle,
        private readonly string $excerpt,
        private readonly string $status,
        private readonly string $commentStatus,
        private readonly string $pingStatus,
        private readonly string $password,
        private readonly string $name,
        private readonly string $toPing,
        private readonly string $pinged,
        private readonly string $modified,
        private readonly string $modifiedGmt,
        private readonly string $contentFiltered,
        private readonly int $parent,
        private readonly string $guid,
        private readonly int $menuOrder,
        private readonly string $postType,
        private readonly string $mimeType,
        private readonly string $commentCount,
        private readonly string $filter,
        private readonly int $dbId,
        private readonly string $menuItemParent,
        private readonly string $objectId,
        private readonly string $object,
        private readonly string $type,
        private readonly string $typeLabel,
        private readonly string $title,
        private readonly string $url,
        private readonly string $target,
        private readonly string $attrTitle,
        private readonly string $description,
        private readonly array $classes,
        private readonly string $xfn
    ) {}


    /**
     * Mendapatkan ID dari menu item
     *
     * @return int ID menu item
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Mendapatkan post author dari menu item
     *
     * @return string Author menu item
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Mendapatkan post date dari menu item
     *
     * @return string Tanggal pembuatan menu item
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Mendapatkan post date GMT dari menu item
     *
     * @return string Tanggal pembuatan menu item dalam format GMT
     */
    public function getDateGmt(): string
    {
        return $this->dateGmt;
    }

    /**
     * Mendapatkan post content dari menu item
     *
     * @return string Konten menu item
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Mendapatkan post title dari menu item
     *
     * @return string Judul menu item
     */
    public function getPostTitle(): string
    {
        return $this->postTitle;
    }

    /**
     * Mendapatkan post excerpt dari menu item
     *
     * @return string Kutipan menu item
     */
    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    /**
     * Mendapatkan post status dari menu item
     *
     * @return string Status menu item
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Mendapatkan comment status dari menu item
     *
     * @return string Status komentar menu item
     */
    public function getCommentStatus(): string
    {
        return $this->commentStatus;
    }

    /**
     * Mendapatkan ping status dari menu item
     *
     * @return string Status ping menu item
     */
    public function getPingStatus(): string
    {
        return $this->pingStatus;
    }

    /**
     * Mendapatkan post password dari menu item
     *
     * @return string Password menu item
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Mendapatkan post name dari menu item
     *
     * @return string Nama/slug menu item
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Mendapatkan to ping dari menu item
     *
     * @return string To ping menu item
     */
    public function getToPing(): string
    {
        return $this->toPing;
    }

    /**
     * Mendapatkan pinged dari menu item
     *
     * @return string Pinged menu item
     */
    public function getPinged(): string
    {
        return $this->pinged;
    }

    /**
     * Mendapatkan post modified dari menu item
     *
     * @return string Tanggal modifikasi menu item
     */
    public function getModified(): string
    {
        return $this->modified;
    }

    /**
     * Mendapatkan post modified GMT dari menu item
     *
     * @return string Tanggal modifikasi menu item dalam format GMT
     */
    public function getModifiedGmt(): string
    {
        return $this->modifiedGmt;
    }

    /**
     * Mendapatkan post content filtered dari menu item
     *
     * @return string Konten yang telah difilter
     */
    public function getContentFiltered(): string
    {
        return $this->contentFiltered;
    }

    /**
     * Mendapatkan post parent dari menu item
     *
     * @return int ID parent menu item
     */
    public function getParent(): int
    {
        return $this->parent;
    }

    /**
     * Mendapatkan GUID dari menu item
     *
     * @return string GUID menu item
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * Mendapatkan menu order dari menu item
     *
     * @return int Urutan menu item
     */
    public function getMenuOrder(): int
    {
        return $this->menuOrder;
    }

    /**
     * Mendapatkan post type dari menu item
     *
     * @return string Tipe post menu item
     */
    public function getPostType(): string
    {
        return $this->postType;
    }

    /**
     * Mendapatkan post mime type dari menu item
     *
     * @return string Mime type menu item
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Mendapatkan comment count dari menu item
     *
     * @return string Jumlah komentar
     */
    public function getCommentCount(): string
    {
        return $this->commentCount;
    }

    /**
     * Mendapatkan filter dari menu item
     *
     * @return string Filter yang digunakan
     */
    public function getFilter(): string
    {
        return $this->filter;
    }

    /**
     * Mendapatkan database ID dari menu item
     *
     * @return int Database ID menu item
     */
    public function getDbId(): int
    {
        return $this->dbId;
    }

    /**
     * Mendapatkan menu item parent dari menu item
     *
     * @return string ID parent menu item
     */
    public function getMenuItemParent(): string
    {
        return $this->menuItemParent;
    }

    /**
     * Mendapatkan object ID dari menu item
     *
     * @return string ID objek yang direferensikan
     */
    public function getObjectId(): string
    {
        return $this->objectId;
    }

    /**
     * Mendapatkan object dari menu item
     *
     * @return string Jenis objek yang direferensikan
     */
    public function getObject(): string
    {
        return $this->object;
    }

    /**
     * Mendapatkan type dari menu item
     *
     * @return string Tipe menu item
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Mendapatkan type label dari menu item
     *
     * @return string Label tipe menu item
     */
    public function getTypeLabel(): string
    {
        return $this->typeLabel;
    }

    /**
     * Mendapatkan title dari menu item
     *
     * @return string Judul menu item
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Mendapatkan URL dari menu item
     *
     * @return string URL menu item
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Mendapatkan target dari menu item
     *
     * @return string Target link (_blank, _self, dll)
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * Mendapatkan attr title dari menu item
     *
     * @return string Title atribut
     */
    public function getAttrTitle(): string
    {
        return $this->attrTitle;
    }

    /**
     * Mendapatkan description dari menu item
     *
     * @return string Deskripsi menu item
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Mendapatkan classes dari menu item
     *
     * @return array Kelas CSS untuk menu item
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * Mendapatkan XFN dari menu item
     *
     * @return string Nilai XFN (XHTML Friends Network)
     */
    public function getXfn(): string
    {
        return $this->xfn;
    }

    /**
     * Membuat instance MenuItemEntityDto dari hasil query database
     *
     * @param mixed $data Data dari hasil query database
     * @return static Instance MenuItemEntityDto baru
     * @throws InvalidArgumentException Jika data yang diperlukan tidak tersedia
     */
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

    /**
     * Membuat instance MenuItemEntityDto dari array data
     *
     * @param array $data Data array untuk membuat instance
     * @return self Instance MenuItemEntityDto baru
     * @throws InvalidArgumentException Jika data yang diperlukan tidak tersedia
     */
    public static function fromArray(array $data): static
    {
        // Memvalidasi data yang diperlukan
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
            postTitle: $data['post_title'],
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
            postType: $data['post_type'] ?? '',
            mimeType: $data['post_mime_type'] ?? '',
            commentCount: $data['comment_count'] ?? '0',
            filter: $data['filter'] ?? '',
            dbId: (int) ($data['db_id'] ?? 0),
            menuItemParent: $data['menu_item_parent'] ?? '0',
            objectId: $data['object_id'] ?? '',
            object: $data['object'] ?? '',
            type: $data['type'] ?? '',
            typeLabel: $data['type_label'] ?? '',
            title: $data['title'] ?? '',
            url: $data['url'] ?? '',
            target: $data['target'] ?? '',
            attrTitle: $data['attr_title'] ?? '',
            description: $data['description'] ?? '',
            classes: $data['classes'] ?? [],
            xfn: $data['xfn'] ?? ''
        );
    }

    /**
     * Mengkonversi instance MenuItemEntityDto ke array
     *
     * @return array Representasi array dari objek
     */
    public function toArray(): array
    {
        return [
            'ID' => $this->getId(),
            'post_author' => $this->getAuthor(),
            'post_date' => $this->getDate(),
            'post_date_gmt' => $this->getDateGmt(),
            'post_content' => $this->getContent(),
            'post_title' => $this->getPostTitle(),
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
            'post_type' => $this->getPostType(),
            'post_mime_type' => $this->getMimeType(),
            'comment_count' => $this->getCommentCount(),
            'filter' => $this->getFilter(),
            'db_id' => $this->getDbId(),
            'menu_item_parent' => $this->getMenuItemParent(),
            'object_id' => $this->getObjectId(),
            'object' => $this->getObject(),
            'type' => $this->getType(),
            'type_label' => $this->getTypeLabel(),
            'title' => $this->getTitle(),
            'url' => $this->getUrl(),
            'target' => $this->getTarget(),
            'attr_title' => $this->getAttrTitle(),
            'description' => $this->getDescription(),
            'classes' => $this->getClasses(),
            'xfn' => $this->getXfn()
        ];
    }
}
