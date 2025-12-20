<?php

namespace Vigihdev\WpCliModels\Contracts\Fields;

interface FieldInterface
{
    /**
     * Get the attributes of the fields.
     *
     * @return string[]
     */
    public function getAttributes(): array;

    /**
     * Get the attributes of the fields.
     *
     * @return array<string, string>
     */
    public function getFieldAttributes(): array;

    /**
     * Transform the data from array assoc to object.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function transform(array $data): array;
}
