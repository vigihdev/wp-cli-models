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
        file_put_contents($path, json_encode($this->items, JSON_PRETTY_PRINT));
    }
}
