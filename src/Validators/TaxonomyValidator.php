<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Entities\TaxonomyEntity;
use Vigihdev\WpCliModels\Exceptions\TaxonomyException;

final class TaxonomyValidator
{

    private array $registeredTaxonomies = [];

    /**
     * Konstruktor untuk inisialisasi taxonomies terdaftar
     */
    public function __construct(
        private readonly string $taxonomy
    ) {
        if (empty($this->registeredTaxonomies)) {
            $this->registeredTaxonomies = array_values(TaxonomyEntity::findAll());
        }
    }

    /**
     * Factory method untuk membuat instance validator
     * 
     * @param string $taxonomy
     * @return self
     */
    public static function validate(string $taxonomy): self
    {
        return new self($taxonomy);
    }

    /**
     * Validasi bahwa taxonomy ada di taxonomies terdaftar
     * 
     * @return self
     * @throws TaxonomyException
     */
    public function mustExist(): self
    {
        if (!in_array($this->taxonomy, $this->registeredTaxonomies, true)) {
            throw TaxonomyException::notFound($this->taxonomy);
        }
        return $this;
    }


    /**
     * Validasi bahwa taxonomy valid dan terdaftar
     * 
     * @throws TaxonomyException
     */
    public function mustBeValid(): self
    {
        if (!in_array($this->taxonomy, $this->registeredTaxonomies, true)) {
            throw TaxonomyException::invalidTaxonomy($this->taxonomy, $this->registeredTaxonomies);
        }

        return $this;
    }

    /**
     * Validasi bahwa taxonomy support hierarchical
     * 
     * @return self
     * @throws TaxonomyException
     */
    public function mustSupportHierarchical(): self
    {
        $taxonomyObject = get_taxonomy($this->taxonomy);

        if (!$taxonomyObject || !$taxonomyObject->hierarchical) {
            throw TaxonomyException::invalidTaxonomy(
                $this->taxonomy,
                $this->registeredTaxonomies,
                "Taxonomy tidak support hierarchical"
            );
        }

        return $this;
    }

    /**
     * Validasi bahwa taxonomy support public query
     * 
     * @return self
     * @throws TaxonomyException
     */
    public function mustBePublicQueryable(): self
    {
        $taxonomyObject = get_taxonomy($this->taxonomy);

        if (!$taxonomyObject || !$taxonomyObject->publicly_queryable) {
            throw TaxonomyException::invalidTaxonomy(
                $this->taxonomy,
                $this->registeredTaxonomies,
                "Taxonomy tidak bisa di-query secara public"
            );
        }

        return $this;
    }

    /**
     * Validasi bahwa taxonomy memiliki capability type yang valid
     * 
     * @param string $capabilityType
     * @return self
     * @throws TaxonomyException
     */
    public function mustHaveCapabilityType(string $capabilityType): self
    {
        $taxonomyObject = get_taxonomy($this->taxonomy);

        if (!$taxonomyObject || $taxonomyObject->capability_type !== $capabilityType) {
            throw TaxonomyException::invalidTaxonomy(
                $this->taxonomy,
                $this->registeredTaxonomies,
                "Capability type tidak sesuai"
            );
        }

        return $this;
    }

    /**
     * Validasi bahwa taxonomy memiliki post types yang valid
     * 
     * @param array $postTypes
     * @return self
     * @throws TaxonomyException
     */
    public function mustHavePostTypes(array $postTypes): self
    {
        $taxonomyObject = get_taxonomy($this->taxonomy);

        if (!$taxonomyObject || array_diff($postTypes, $taxonomyObject->object_type) !== []) {
            throw TaxonomyException::invalidTaxonomy(
                $this->taxonomy,
                $this->registeredTaxonomies,
                "Post types tidak sesuai"
            );
        }

        return $this;
    }

    /**
     * Validasi bahwa taxonomy belum terdaftar
     * 
     * @return self
     * @throws TaxonomyException
     */
    public function mustNotExist(): self
    {
        if (in_array($this->taxonomy, $this->registeredTaxonomies, true)) {
            throw TaxonomyException::alreadyExists($this->taxonomy);
        }

        return $this;
    }
}
