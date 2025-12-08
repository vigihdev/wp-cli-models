<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Formatters;

use cli\Table;
use cli\table\Tabular;
use cli\table\Ascii;

final class TableFormatter extends BaseFormater
{

    public function __construct(
        private readonly array $items,
        private readonly array $fields,
        ?string $filepath = null
    ) {
        parent::__construct($filepath);
    }

    public function display(): string
    {
        $path = $this->getFilePath();

        $this->write($path);

        if (!is_file($path)) {
            return '';
        }

        $content = file_get_contents($path);

        // Hapus file setelah dibaca
        @unlink($path);

        return $content;
    }

    protected function write(string $path): void
    {
        $table = new Table();
    }

    private function extract($item): array
    {
        $result = [];

        foreach ($this->fields as $field) {
            $result[] = is_object($item) ? $item->$field ?? null : $item[$field] ?? null;
        }

        return $result;
    }
}
