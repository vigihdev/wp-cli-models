<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Args;

use Vigihdev\WpCliModels\Contracts\ArrayAbleInterface;

interface MenuItemArgsInterface extends ArrayAbleInterface
{
    public function getTitle(): string;
    public function getUrl(): ?string;
    public function getParentId(): int;
    public function getTarget(): ?string;
    public function getClasses(): ?string;
    public function getAttrTitle(): ?string;
    public function getType(): ?string;
    public function getObjectId(): int;
    public function getObjectType(): ?string;
}
