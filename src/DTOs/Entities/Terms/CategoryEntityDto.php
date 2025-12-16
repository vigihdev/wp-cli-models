<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Entities\Terms;

use InvalidArgumentException;
use Vigihdev\WpCliModels\Contracts\Entities\Terms\CategoryEntityInterface;
use Vigihdev\WpCliModels\DTOs\Entities\BaseEntityDto;

/**
 * Represents a category entity.
 * 
 * @see CategoryEntityInterface
 */
final class CategoryEntityDto extends BaseEntityDto implements CategoryEntityInterface
{


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
        private readonly string $filter,
        private readonly int $catId,
        private readonly int $categoryCount,
        private readonly string $categoryDescription,
        private readonly string $catName,
        private readonly string $categoryNicename,
        private readonly int $categoryParent
    ) {}

    /**
     * {@inheritDoc}
     */
    public function termId(): int
    {
        return $this->termId;
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * {@inheritDoc}
     */
    public function termGroup(): int
    {
        return $this->termGroup;
    }

    /**
     * {@inheritDoc}
     */
    public function parent(): int
    {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->count;
    }

    public function filter(): string
    {
        return $this->filter;
    }

    /**
     * {@inheritDoc}
     */
    public function catId(): int
    {
        return $this->catId;
    }

    /**
     * {@inheritDoc}
     */
    public function categoryCount(): int
    {
        return $this->categoryCount;
    }

    /**
     * {@inheritDoc}
     */
    public function categoryDescription(): string
    {
        return $this->categoryDescription;
    }

    /**
     * {@inheritDoc}
     */
    public function catName(): string
    {
        return $this->catName;
    }

    /**
     * {@inheritDoc}
     */
    public function categoryNicename(): string
    {
        return $this->categoryNicename;
    }

    /**
     * {@inheritDoc}
     */
    public function categoryParent(): int
    {
        return $this->categoryParent;
    }

    /**
     * {@inheritDoc}
     */
    public function description(): string
    {
        return $this->description;
    }

    public function termTaxonomyId(): int
    {
        return $this->termTaxonomyId;
    }

    /**
     * {@inheritDoc}
     */
    public function taxonomy(): string
    {
        return $this->taxonomy;
    }

    private function getAttributes(): array
    {
        return [
            'term_id',
            'name',
            'slug',
            'term_group',
            'term_taxonomy_id',
            'taxonomy',
            'description',
            'parent',
            'count',
            'filter',
            'cat_ID',
            'category_count',
            'category_description',
            'cat_name',
            'category_nicename',
            'category_parent',
        ];
    }

    /**
     * Creates a new instance of the class from a query result.
     *
     * @param mixed $data The data to create the instance from.
     * @return static 
     * @throws InvalidArgumentException if the data is not an array or object
     */
    public static function fromQuery(mixed $data): static
    {

        if (is_object($data)) {
            $data = get_object_vars($data);
            $data = array_change_key_case($data, CASE_LOWER);
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('Data must be an array or object');
        }

        return self::fromArray($data);
    }

    /**
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new self(
            termId: (int) ($data['term_id'] ?? 0),
            name: $data['name'] ?? '',
            slug: $data['slug'] ?? '',
            termGroup: (int) ($data['term_group'] ?? 0),
            termTaxonomyId: (int) ($data['term_taxonomy_id'] ?? 0),
            taxonomy: $data['taxonomy'] ?? '',
            description: $data['description'] ?? '',
            parent: (int) ($data['parent'] ?? 0),
            count: (int) ($data['count'] ?? 0),
            filter: $data['filter'] ?? 'raw',
            catId: (int) ($data['cat_id'] ?? 0),
            categoryCount: (int) ($data['category_count'] ?? 0),
            categoryDescription: $data['category_description'] ?? '',
            catName: $data['cat_name'] ?? '',
            categoryNicename: $data['category_nicename'] ?? '',
            categoryParent: (int) ($data['category_parent'] ?? 0)
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'term_id' => $this->termId,
            'name' => $this->name,
            'slug' => $this->slug,
            'term_group' => $this->termGroup,
            'term_taxonomy_id' => $this->termTaxonomyId,
            'taxonomy' => $this->taxonomy,
            'description' => $this->description,
            'parent' => $this->parent,
            'count' => $this->count,
            'filter' => $this->filter,
            'cat_ID' => $this->catId,
            'category_count' => $this->categoryCount,
            'category_description' => $this->categoryDescription,
            'cat_name' => $this->catName,
            'category_nicename' => $this->categoryNicename,
            'category_parent' => $this->categoryParent,
        ];
    }
}
