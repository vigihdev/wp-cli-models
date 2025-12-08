<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Formatters;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

abstract class BaseFormater
{
    protected ?string $filepath;

    public function __construct(?string $filepath = null)
    {
        $this->filepath = $filepath;
    }

    public function display(): string
    {
        $path = $this->getFilePath();

        $this->write($path);

        if (! is_file($path)) {
            return '';
        }

        return file_get_contents($path);
    }

    /**
     * Formatter must implement how the file is written.
     */
    abstract protected function write(string $path): void;

    /**
     * Resolve path: use user filepath or tmp file.
     */
    protected function getFilePath(): string
    {
        if ($this->filepath) {
            return $this->filepath;
        }

        $tmpDir = Path::join(sys_get_temp_dir(), (string) crc32(static::class));

        if (! is_dir($tmpDir)) {
            (new Filesystem())->mkdir($tmpDir);
        }

        return Path::join($tmpDir, $this->getTmpName());
    }

    protected function getTmpName(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName()) . '_' . uniqid() . '.tmp';
    }
}
