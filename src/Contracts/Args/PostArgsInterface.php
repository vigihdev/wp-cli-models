<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Args;

use Vigihdev\WpCliModels\Contracts\ArgsInterface;
use Vigihdev\WpCliModels\Enums\{PostStatus, PostType};

interface PostArgsInterface extends ArgsInterface
{
    public function getTitle(): string;
    public function getType(): ?PostType;
    public function getStatus(): ?PostStatus;
    public function getContent(): ?string;
    public function getSlug(): ?string;
    public function getExcerpt(): ?string;
    public function getAuthor(): ?int;
    public function getCategories(): array;
    public function getTags(): array;
    public function getFeaturedImage(): ?int;
    public function getDate(): ?\DateTimeInterface;

    // Helper methods untuk WordPress
    public function getTypeValue(): ?string;
    public function getStatusValue(): ?string;
    public function getDateString(): ?string;
}
