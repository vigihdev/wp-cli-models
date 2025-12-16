<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Entities;

final class TaxonomyEntity
{

    /**
     * Mengambil semua taxonomy dalam bentuk array
     *
     * @return array<string, string> Array dari slug taxonomy dan label taxonomy yang ada
     */
    public static function findAll(): array
    {
        return get_taxonomies();
    }

    /**
     * Mencari taxonomy berdasarkan slug
     *
     * @param string $taxonomy Slug taxonomy
     * @return string|null Slug taxonomy jika ditemukan, null jika tidak
     */
    public static function get(string $taxonomy): ?string
    {
        return self::findAll()[$taxonomy] ?? null;
    }

    /**
     * Memeriksa apakah taxonomy ada
     *
     * @param string $taxonomy Slug taxonomy
     * @return bool True jika taxonomy ada, false jika tidak
     */
    public static function exists(string $taxonomy): bool
    {
        return self::get($taxonomy) !== null;
    }
}
