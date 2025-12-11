<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

abstract class WpCliModelException extends \RuntimeException
{
    protected array $context = [];

    public function __construct(
        string $message,
        array $context = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function withContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }
}
