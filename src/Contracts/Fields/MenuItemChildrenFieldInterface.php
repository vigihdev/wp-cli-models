<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Fields;


interface MenuItemChildrenFieldInterface extends MenuItemFieldInterface
{
    public function getParent(): int;
}
