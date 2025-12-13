<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Formatters;

use Vigihdev\Support\File;
use Vigihdev\WpCliModels\Contracts\Formatters\FormatterInterface;

final class JsonFormatter extends BaseFormater implements FormatterInterface
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

    public function save(): bool
    {
        if (!$this->filepath) {
            return false;
        }

        $dirPath = dirname($this->filepath);
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
        $items = [];
        foreach ($this->items as $item) {

            if (is_object($item)) {
                $item = get_object_vars($item);
            }

            if (is_array($item)) {
                $items[] = array_filter($item, fn($key) => in_array($key, $this->fields), ARRAY_FILTER_USE_KEY);
            }
        }

        file_put_contents($path, json_encode($items, JSON_PRETTY_PRINT));
    }
}
