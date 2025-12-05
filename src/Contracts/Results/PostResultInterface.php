<?php

declare(strict_types=1);

namespace Vigihdev\WpCliMake\Contracts\Results;


interface PostResultInterface
{

    public function getStatus(): bool;
    public function isValid(): bool;
    public function getMessage(): string;
}
