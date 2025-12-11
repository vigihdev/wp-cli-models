<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

enum MenuItemType: string
{
    /**
     * Tautan kustom (URL eksternal, hash, atau URL bebas).
     */
    case CUSTOM = 'custom';

    /**
     * Item yang menautkan ke Halaman, Pos, atau Custom Post Type individual.
     * Secara teknis di WordPress: 'post_type'
     */
    case POST_TYPE = 'post_type';

    /**
     * Item yang menautkan ke arsip taksonomi, seperti Kategori, Tag, 
     * atau Custom Taxonomy lainnya.
     * Secara teknis di WordPress: 'taxonomy'
     */
    case TAXONOMY = 'taxonomy';

    /**
     * Item yang menautkan ke halaman indeks/arsip untuk 
     * Custom Post Type tertentu (misalnya, /blog/, /produk/).
     * Secara teknis di WordPress: 'post_type_archive'
     */
    case POST_TYPE_ARCHIVE = 'post_type_archive';

    /**
     * (Kurang umum) Menautkan ke arsip format pos (Video, Galeri, dll.).
     * Secara teknis di WordPress: 'post_format'
     */
    case POST_FORMAT = 'post_format';
}
