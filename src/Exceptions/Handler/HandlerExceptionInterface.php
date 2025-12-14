<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions\Handler;

use Throwable;
use Vigihdev\WpCliModels\UI\CliStyle;

interface HandlerExceptionInterface
{

    public function handle(CliStyle $io, Throwable $e): void;
}
