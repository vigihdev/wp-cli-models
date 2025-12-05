<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Args;

use Vigihdev\WpCliModels\Contracts\ArrayCompactAbleInterface;

interface MenuArgsInterface extends ArrayCompactAbleInterface
{
    public function getName(): string;
    public function getSlug(): ?string;
    public function getDescription(): ?string;
    public function getLocation(): ?string;
}
