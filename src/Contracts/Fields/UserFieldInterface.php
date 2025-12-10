<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Contracts\Fields;

interface UserFieldInterface
{

    public function getUsername(): string;
    public function getEmail(): string;
}
