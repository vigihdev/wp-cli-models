<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Fields;

use DateTime;
use Vigihdev\WpCliModels\Contracts\Able\ArrayAbleInterface;
use Vigihdev\WpCliModels\Contracts\Fields\DefaultPostFieldInterface;

final class DefaultPostFieldDto implements DefaultPostFieldInterface, ArrayAbleInterface
{

    public function __construct(
        private readonly string $title,
        private readonly ?string $excerpt = null
    ) {}

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function toArray(): array
    {

        $postDefault = [
            'post_title'        => sanitize_text_field($this->title),
            'post_excerpt'      => wp_kses_post($this->excerpt ?? ''),
            'post_date'         => get_date_from_gmt((new DateTime('now'))->format(DATE_W3C)),
            'post_date_gmt'     => get_gmt_from_date((new DateTime('now'))->format(DATE_W3C)),
            'post_modified'     => get_date_from_gmt((new DateTime('now'))->format(DATE_W3C)),
            'post_modified_gmt' => get_gmt_from_date((new DateTime('now'))->format(DATE_W3C)),
            'post_name'         => sanitize_title($this->title),
            'post_parent'       => 0,
            'comment_status'    => get_option('default_comment_status', 'open'),
            'ping_status'       => get_option('default_ping_status', 'open'),
        ];

        return $postDefault;
    }
}
