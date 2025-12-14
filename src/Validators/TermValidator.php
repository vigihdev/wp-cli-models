<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Exceptions\TermException;

final class TermValidator
{
    public function __construct(
        private readonly int|string $identifier,
        private readonly string $taxonomy
    ) {}

    public static function validate(int|string $identifier, string $taxonomy): static
    {
        return new self($identifier, $taxonomy);
    }

    /**
     * Validasi bahwa taxonomy terdaftar di WordPress
     * 
     * @throws TermException
     */
    public function mustHaveValidTaxonomy(): self
    {
        if (!taxonomy_exists($this->taxonomy)) {
            $registeredTaxonomies = get_taxonomies(['public' => true], 'names');
            throw TermException::invalidTaxonomy($this->taxonomy, $registeredTaxonomies);
        }

        return $this;
    }

    /**
     * Validasi bahwa term dengan identifier tertentu ada
     * 
     * @throws TermException
     */
    public function mustExist(): self
    {
        $term = $this->getTerm();

        if (!$term || is_wp_error($term)) {
            $termId = is_numeric($this->identifier) ? (int) $this->identifier : 0;
            throw TermException::notFound($termId, $this->taxonomy);
        }

        return $this;
    }

    /**
     * Validasi bahwa term dengan nama tertentu belum ada
     * 
     * @throws TermException
     */
    public function mustNotExist(string $name): self
    {
        $term = term_exists($name, $this->taxonomy);

        if ($term) {
            throw TermException::alreadyExists($name, $this->taxonomy);
        }

        return $this;
    }

    /**
     * Validasi bahwa nama term unik dalam taxonomy
     * 
     * @throws TermException
     */
    public function mustHaveUniqueName(string $name, ?int $excludeTermId = null): self
    {
        $term = get_term_by('name', $name, $this->taxonomy);

        if ($term && !is_wp_error($term)) {
            // Jika ada excludeTermId, cek apakah term yang ditemukan berbeda
            if ($excludeTermId && $term->term_id !== $excludeTermId) {
                throw TermException::alreadyExists($name, $this->taxonomy);
            } elseif (!$excludeTermId) {
                throw TermException::alreadyExists($name, $this->taxonomy);
            }
        }

        return $this;
    }

    /**
     * Validasi bahwa slug term unik dalam taxonomy
     * 
     * @throws TermException
     */
    public function mustHaveUniqueSlug(string $slug, ?int $excludeTermId = null): self
    {
        $term = get_term_by('slug', $slug, $this->taxonomy);

        if ($term && !is_wp_error($term)) {
            if ($excludeTermId && $term->term_id !== $excludeTermId) {
                throw TermException::duplicateSlug($slug, $this->taxonomy);
            } elseif (!$excludeTermId) {
                throw TermException::duplicateSlug($slug, $this->taxonomy);
            }
        }

        return $this;
    }

    /**
     * Validasi nama term tidak kosong dan valid
     * 
     * @throws TermException
     */
    public function mustHaveValidName(string $name): self
    {
        $name = trim($name);

        if (empty($name)) {
            throw TermException::createFailed('', $this->taxonomy, 'Nama term tidak boleh kosong');
        }

        if (strlen($name) > 200) {
            throw TermException::createFailed(
                $name,
                $this->taxonomy,
                'Nama term terlalu panjang (maksimal 200 karakter)'
            );
        }

        return $this;
    }

    /**
     * Validasi bahwa parent term ada dan valid
     * 
     * @throws TermException
     */
    public function mustHaveValidParent(int $parentId): self
    {
        if ($parentId <= 0) {
            return $this; // Parent 0 = top-level term, valid
        }

        // Cek apakah taxonomy support hierarchy
        $taxonomyObject = get_taxonomy($this->taxonomy);
        if (!$taxonomyObject || !$taxonomyObject->hierarchical) {
            throw TermException::invalidParent(0, $parentId, $this->taxonomy);
        }

        // Cek apakah parent term ada
        $parentTerm = get_term($parentId, $this->taxonomy);

        if (!$parentTerm || is_wp_error($parentTerm)) {
            throw TermException::parentNotFound($parentId, $this->taxonomy);
        }

        return $this;
    }

    /**
     * Validasi bahwa tidak ada circular reference dalam hierarchy
     * 
     * @throws TermException
     */
    public function mustNotCreateCircularReference(int $termId, int $parentId): self
    {
        if ($termId === $parentId) {
            throw TermException::invalidParent($termId, $parentId, $this->taxonomy);
        }

        // Cek apakah parentId adalah descendant dari termId
        $currentParent = $parentId;
        $maxDepth = 10; // Prevent infinite loop
        $depth = 0;

        while ($currentParent > 0 && $depth < $maxDepth) {
            if ($currentParent === $termId) {
                throw TermException::invalidParent($termId, $parentId, $this->taxonomy);
            }

            $parentTerm = get_term($currentParent, $this->taxonomy);
            $currentParent = ($parentTerm && !is_wp_error($parentTerm)) ? (int) $parentTerm->parent : 0;
            $depth++;
        }

        return $this;
    }

    /**
     * Validasi untuk create term
     * 
     * @throws TermException
     */
    public function validateForCreate(
        string $name,
        ?string $slug = null,
        ?int $parentId = null
    ): self {
        $this->mustHaveValidTaxonomy();
        $this->mustHaveValidName($name);
        $this->mustNotExist($name);

        if ($slug !== null && !empty($slug)) {
            $this->mustHaveUniqueSlug($slug);
        }

        if ($parentId !== null && $parentId > 0) {
            $this->mustHaveValidParent($parentId);
        }

        return $this;
    }

    /**
     * Validasi untuk update term
     * 
     * @throws TermException
     */
    public function validateForUpdate(
        ?string $newName = null,
        ?string $newSlug = null,
        ?int $newParentId = null
    ): self {
        $this->mustHaveValidTaxonomy();
        $this->mustExist();

        $term = $this->getTerm();
        if (!$term || is_wp_error($term)) {
            return $this;
        }

        if ($newName !== null) {
            $this->mustHaveValidName($newName);
            $this->mustHaveUniqueName($newName, $term->term_id);
        }

        if ($newSlug !== null && !empty($newSlug)) {
            $this->mustHaveUniqueSlug($newSlug, $term->term_id);
        }

        if ($newParentId !== null) {
            $this->mustHaveValidParent($newParentId);

            if ($newParentId > 0) {
                $this->mustNotCreateCircularReference($term->term_id, $newParentId);
            }
        }

        return $this;
    }

    /**
     * Validasi untuk delete term
     * 
     * @throws TermException
     */
    public function validateForDelete(): self
    {
        $this->mustHaveValidTaxonomy();
        $this->mustExist();

        return $this;
    }

    /**
     * Validasi bahwa term tidak sedang digunakan oleh posts
     * 
     * @throws TermException
     */
    public function mustNotBeUsedByPosts(): self
    {
        $this->mustExist();

        $term = $this->getTerm();
        if ($term && !is_wp_error($term) && $term->count > 0) {
            throw TermException::deleteFailed(
                $term->term_id,
                $this->taxonomy,
                sprintf('Term sedang digunakan oleh %d post(s)', $term->count)
            );
        }

        return $this;
    }

    /**
     * Validasi bahwa term tidak memiliki child terms
     * 
     * @throws TermException
     */
    public function mustNotHaveChildren(): self
    {
        $this->mustExist();

        $term = $this->getTerm();
        if ($term && !is_wp_error($term)) {
            $children = get_terms([
                'taxonomy' => $this->taxonomy,
                'parent' => $term->term_id,
                'hide_empty' => false,
                'fields' => 'ids'
            ]);

            if (!empty($children) && !is_wp_error($children)) {
                throw TermException::deleteFailed(
                    $term->term_id,
                    $this->taxonomy,
                    sprintf('Term memiliki %d child term(s)', count($children))
                );
            }
        }

        return $this;
    }

    /**
     * Helper method untuk mendapatkan term object
     * 
     * @return \WP_Term|false|\WP_Error|null
     */
    private function getTerm(): \WP_Term|false|\WP_Error|null
    {
        if (is_numeric($this->identifier)) {
            return get_term((int) $this->identifier, $this->taxonomy);
        }

        return get_term_by('slug', (string) $this->identifier, $this->taxonomy);
    }

    /**
     * Validasi bahwa taxonomy support hierarchy
     * 
     * @throws TermException
     */
    public function mustSupportHierarchy(): self
    {
        $taxonomyObject = get_taxonomy($this->taxonomy);

        if (!$taxonomyObject || !$taxonomyObject->hierarchical) {
            throw TermException::invalidParent(
                0,
                0,
                $this->taxonomy
            );
        }

        return $this;
    }
}
