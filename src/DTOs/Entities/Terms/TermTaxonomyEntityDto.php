<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Entities\Terms;

use Vigihdev\WpCliModels\Contracts\Entities\Terms\TermTaxonomyEntityInterface;
use Vigihdev\WpCliModels\DTOs\Entities\BaseEntityDto;
use InvalidArgumentException;

/**
 * Class TermTaxonomyEntityDto
 *
 * DTO untuk menyimpan dan mengakses data term taxonomy entity
 */
final class TermTaxonomyEntityDto extends BaseEntityDto implements TermTaxonomyEntityInterface
{
    /**
     * Membuat instance objek TermTaxonomyEntityDto dengan parameter yang ditentukan
     *
     * @param int $termTaxonomyId ID dari term taxonomy
     * @param int $termId ID dari term
     * @param string $taxonomy Nama taksonomi
     * @param string $description Deskripsi taksonomi
     * @param int $parent ID parent
     * @param int $count Jumlah item
     */
    public function __construct(
        private readonly int $termTaxonomyId,
        private readonly int $termId,
        private readonly string $taxonomy,
        private readonly string $description,
        private readonly int $parent,
        private readonly int $count
    ) {}

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
     * Mendapatkan ID dari term
     *
     * @return int ID dari term
     */
    public function getTermId(): int
    {
        return $this->termId;
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
     * Membuat instance TermTaxonomyEntityDto dari array data
     *
     * @param array $data Data array yang berisi informasi term taxonomy
     * @return static Instance TermTaxonomyEntityDto baru
     * @throws InvalidArgumentException Jika data yang diperlukan tidak tersedia
     */
    public static function fromArray(array $data): static
    {
        if (!isset($data['term_taxonomy_id'])) {
            throw new InvalidArgumentException('Field term_taxonomy_id is required');
        }

        if (!isset($data['term_id'])) {
            throw new InvalidArgumentException('Field term_id is required');
        }

        if (!isset($data['taxonomy'])) {
            throw new InvalidArgumentException('Field taxonomy is required');
        }

        return new static(
            termTaxonomyId: (int) $data['term_taxonomy_id'],
            termId: (int) $data['term_id'],
            taxonomy: (string) $data['taxonomy'],
            description: (string) ($data['description'] ?? ''),
            parent: (int) ($data['parent'] ?? 0),
            count: (int) ($data['count'] ?? 0)
        );
    }

    /**
     * Membuat instance TermTaxonomyEntityDto dari hasil query database
     *
     * @param mixed $data Data dari hasil query database
     * @return static Instance TermTaxonomyEntityDto baru
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
     * Mengkonversi objek TermTaxonomyEntityDto menjadi array
     *
     * @return array Representasi array dari objek TermTaxonomyEntityDto
     */
    public function toArray(): array
    {
        return [
            'term_taxonomy_id' => $this->getTermTaxonomyId(),
            'term_id' => $this->getTermId(),
            'taxonomy' => $this->getTaxonomy(),
            'description' => $this->getDescription(),
            'parent' => $this->getParent(),
            'count' => $this->getCount(),
        ];
    }
}
