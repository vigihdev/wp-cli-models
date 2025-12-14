<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions\Handler;

use Throwable;
use Vigihdev\WpCliModels\Exceptions\WpCliModelException;
use Vigihdev\WpCliModels\UI\CliStyle;

final class WpCliExceptionHandler implements HandlerExceptionInterface
{
    private CliStyle $io;
    public function handle(CliStyle $io, Throwable $e): void
    {
        $this->io = $io;
        if ($e instanceof WpCliModelException) {
            $this->printException($e);
            return;
        }

        $io->error(
            'Unexpected error: ' . $e->getMessage()
        );
    }

    private function printException(WpCliModelException $e): void
    {
        $message = implode(' ', [
            $e->getMessage(),
            implode(' ', array_values($e->getContext())),
            $e->getSolution(),
        ]);
        var_dump($message);
    }
}
