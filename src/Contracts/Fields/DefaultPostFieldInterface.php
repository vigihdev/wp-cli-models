<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Fields;

interface DefaultPostFieldInterface
{

    public function getTitle(): string;

    public function getExcerpt(): ?string;
}
