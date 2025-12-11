<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Entities\Menu;

use Vigihdev\WpCliModels\DTOs\Entities\BaseEntityDto;
use InvalidArgumentException;
use Vigihdev\WpCliModels\Contracts\Entities\Menu\MenuEntityInterface;

/**
 * Class MenuEntityDto
 *
 * DTO untuk menyimpan dan mengakses data term entity
 */
final class MenuEntityDto extends BaseEntityDto implements MenuEntityInterface
{
    /**
     * Membuat instance objek MenuEntityDto dengan parameter yang ditentukan
     *
     * @param int $termId ID dari term
     * @param string $name Nama dari term
     * @param string $slug Slug dari term
     * @param int $termGroup Group dari term
     * @param int $termTaxonomyId ID dari term taxonomy
     * @param string $taxonomy Nama taksonomi
     * @param string $description Deskripsi taksonomi
     * @param int $parent ID parent
     * @param int $count Jumlah item
     * @param string $filter Filter yang digunakan
     */
    public function __construct(
        private readonly int $termId,
        private readonly string $name,
        private readonly string $slug,
        private readonly int $termGroup,
        private readonly int $termTaxonomyId,
        private readonly string $taxonomy,
        private readonly string $description,
        private readonly int $parent,
        private readonly int $count,
        private readonly string $filter
    ) {}

    /**
     * Mendapatkan ID dari term
     *
     * @return int ID dari term
     */
    public function getTermId(): int
    {
        return $this->termId;
    }

    /**
     * Mendapatkan nama dari term
     *
     * @return string Nama dari term
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Mendapatkan slug dari term
     *
     * @return string Slug dari term
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Mendapatkan group dari term
     *
     * @return int Group dari term
     */
    public function getTermGroup(): int
    {
        return $this->termGroup;
    }

    /**
     * Mendapatkan ID dari term taxonomy
     *
     * @return int ID dari term taxonomy
     */
    public function getTermTaxonomyId(): int
    {
        return $this->termTaxonomyId;
    }

    /**
     * Mendapatkan nama taksonomi
     *
     * @return string Nama taksonomi
     */
    public function getTaxonomy(): string
    {
        return $this->taxonomy;
    }

    /**
     * Mendapatkan deskripsi taksonomi
     *
     * @return string Deskripsi taksonomi
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Mendapatkan ID parent dari term ini
     *
     * @return int ID parent
     */
    public function getParent(): int
    {
        return $this->parent;
    }

    /**
     * Mendapatkan jumlah item dalam taksonomi ini
     *
     * @return int Jumlah item
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Mendapatkan filter yang digunakan
     *
     * @return string Filter yang digunakan
     */
    public function getFilter(): string
    {
        return $this->filter;
    }

    /**
     * Membuat instance MenuEntityDto dari array data
     *
     * @param array $data Data array yang berisi informasi term
     * @return static Instance MenuEntityDto baru
     * @throws InvalidArgumentException Jika data yang diperlukan tidak tersedia
     */
    public static function fromArray(array $data): static
    {
        if (!isset($data['term_id'])) {
            throw new InvalidArgumentException('Field term_id is required');
        }

        if (!isset($data['name'])) {
            throw new InvalidArgumentException('Field name is required');
        }

        if (!isset($data['slug'])) {
            throw new InvalidArgumentException('Field slug is required');
        }

        if (!isset($data['term_group'])) {
            throw new InvalidArgumentException('Field term_group is required');
        }

        if (!isset($data['term_taxonomy_id'])) {
            throw new InvalidArgumentException('Field term_taxonomy_id is required');
        }

        if (!isset($data['taxonomy'])) {
            throw new InvalidArgumentException('Field taxonomy is required');
        }

        if (!isset($data['description'])) {
            throw new InvalidArgumentException('Field description is required');
        }

        if (!isset($data['parent'])) {
            throw new InvalidArgumentException('Field parent is required');
        }

        if (!isset($data['count'])) {
            throw new InvalidArgumentException('Field count is required');
        }

        if (!isset($data['filter'])) {
            throw new InvalidArgumentException('Field filter is required');
        }

        return new static(
            termId: (int) $data['term_id'],
            name: (string) $data['name'],
            slug: (string) $data['slug'],
            termGroup: (int) $data['term_group'],
            termTaxonomyId: (int) $data['term_taxonomy_id'],
            taxonomy: (string) $data['taxonomy'],
            description: (string) $data['description'],
            parent: (int) $data['parent'],
            count: (int) $data['count'],
            filter: (string) $data['filter']
        );
    }

    /**
     * Membuat instance MenuEntityDto dari hasil query database
     *
     * @param mixed $data Data dari hasil query database
     * @return static Instance MenuEntityDto baru
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
     * Mengkonversi objek MenuEntityDto menjadi array
     *
     * @return array Representasi array dari objek MenuEntityDto
     */
    public function toArray(): array
    {
        return [
            'term_id' => $this->getTermId(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'term_group' => $this->getTermGroup(),
            'term_taxonomy_id' => $this->getTermTaxonomyId(),
            'taxonomy' => $this->getTaxonomy(),
            'description' => $this->getDescription(),
            'parent' => $this->getParent(),
            'count' => $this->getCount(),
            'filter' => $this->getFilter(),
        ];
    }
}
