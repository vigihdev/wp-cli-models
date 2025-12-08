<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Formatters;

use cli\Table;
use cli\table\Tabular;
use cli\table\Ascii;
use Vigihdev\Support\File;
use Vigihdev\WpCliModels\Contracts\Formatters\FormatterInterface;

final class MarkdownTableFormatter extends BaseFormater implements FormatterInterface
{

    private array $defaultWidths = [];
    public function __construct(
        private readonly array $items,
        private readonly array $fields,
        ?string $filepath = null
    ) {
        parent::__construct($filepath);
        $this->defaultWidths = array_map(fn($v) => 100 / count($this->fields), $this->fields);
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

    protected function write(string $path): void
    {
        $table = new Table();

        $table->setRenderer($this->asciiRender());
        $table->setHeaders($this->fields);

        foreach ($this->items as $item) {
            $row = $this->extract($item);
            $table->addRow(array_values($row));
        }

        $items = array_slice($table->getDisplayLines(), 1, -1);
        file_put_contents($path, implode(PHP_EOL, $items));
    }

    private function asciiRender(): Ascii
    {

        $ascii = new Ascii();
        $ascii->setWidths(widths: $this->defaultWidths);
        $ascii->setCharacters([
            'corner'  => '|',
            'line'    => '-',
            'border'  => '|',
            'padding' => ' ',
        ]);

        return $ascii;
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
