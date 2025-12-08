<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Formatters;

use Vigihdev\Support\File;
use Vigihdev\WpCliModels\Contracts\Formatters\FormatterInterface;

final class CsvFormatter extends BaseFormater implements FormatterInterface
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
        $out = fopen($path, 'w');

        if (! $out) {
            return;
        }

        fputcsv($out, $this->fields);

        foreach ($this->items as $item) {
            $row = $this->extract($item);
            fputcsv($out, $row);
        }

        fclose($out);
    }

    public function save(): bool
    {
        if (!$this->filepath) {
            return false;
        }

        $dirPath = pathinfo($this->filepath, PATHINFO_DIRNAME);
        if (! is_dir($dirPath)) {
            return false;
        }

        $path = $this->getFilePath();
        $this->write($path);
        if (!is_file($path)) {
            return false;
        }

        return (bool)File::put($this->filepath, File::get($path));
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
