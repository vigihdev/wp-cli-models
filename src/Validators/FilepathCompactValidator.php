<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use SimplePie\Cache\File;
use Vigihdev\WpCliModels\Exceptions\FileException;
use Vigihdev\WpCliModels\Exceptions\FilepathCompactException;

final class FilepathCompactValidator
{
    public function __construct(
        private readonly string $filepath
    ) {}

    public static function validate(string $filepath): static
    {
        return new self($filepath);
    }

    /**
     * Validasi untuk import (file harus ada dan readable)
     * 
     * @throws FileException
     */
    public function validateForImport(): string
    {
        if (!file_exists($this->filepath)) {
            throw FileException::notFound($this->filepath);
        }

        if (!is_readable($this->filepath)) {
            throw FileException::notReadable($this->filepath);
        }

        return $this->filepath;
    }

    /**
     * Validasi untuk export (directory harus writable)
     * 
     * @throws FileException
     */
    public function validateForExport(bool $overwrite = false): string
    {
        $dir = dirname($this->filepath);

        if (!is_dir($dir) || !is_writable($dir)) {
            throw new FileException(
                "Directory tidak dapat ditulis",
                $dir,
                "Periksa permission directory"
            );
        }

        if (file_exists($this->filepath) && !$overwrite) {
            throw new FileException(
                "File sudah ada",
                $this->filepath,
                "Gunakan --overwrite untuk menimpa file"
            );
        }

        return $this->filepath;
    }

    /**
     * Validasi extension tambahan (optional)
     * 
     * @throws FileException
     */
    public function mustBeJson(): self
    {
        $ext = strtolower(pathinfo($this->filepath, PATHINFO_EXTENSION));
        if ($ext !== 'json') {
            throw FileException::invalidExtension($this->filepath, 'json');
        }
        return $this;
    }

    public function mustExist(): self
    {
        if (!file_exists($this->filepath)) {
            throw FilepathCompactException::notFound($this->filepath);
        }
        return $this;
    }

    public function mustBeFile(): self
    {
        if (!is_file($this->filepath)) {
            throw new FileException(
                "Path bukan merupakan sebuah file",
                $this->filepath,
                "Pastikan path menunjuk ke file yang valid"
            );
        }
        return $this;
    }
}
