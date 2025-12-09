<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\DTOs\Fields;

use Vigihdev\WpCliModels\Contracts\Able\ArrayAbleInterface;
use Vigihdev\WpCliModels\Contracts\Fields\TaxonomiInputFieldInterface;

final class TaxonomiInputDto implements TaxonomiInputFieldInterface, ArrayAbleInterface
{
    public function __construct(
        private readonly array $taxInput,
    ) {}

    /**
     * @return array<string, int[]>
     */
    public function getTaxInput(): array
    {
        return $this->taxInput;
    }

    public function getTaxonomy(): string
    {
        return array_key_first($this->taxInput) ?? '';
    }

    /**
     * @return int[]
     */
    public function getTerms(): array
    {
        return array_values($this->taxInput)[0] ?? [];
    }

    public function toArray(): array
    {
        return [
            'tax_input' => $this->taxInput
        ];
    }
}
