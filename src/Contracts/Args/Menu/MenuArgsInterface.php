<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Args\Menu;


interface MenuArgsInterface
{
    public function getName(): string;
    public function getSlug(): string;
    public function getDescription(): ?string;
    public function getLocation(): ?string;
}
