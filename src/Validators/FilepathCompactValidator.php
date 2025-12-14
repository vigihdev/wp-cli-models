<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

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

    public function mustBeExtension(string $extension): self
    {
        $ext = strtolower(pathinfo($this->filepath, PATHINFO_EXTENSION));
        if ($ext !== $extension) {
            throw FilepathCompactException::invalidExtension($this->filepath, $extension);
        }
        return $this;
    }

    /**
     * Validasi untuk import (file harus ada dan readable)
     * 
     * @throws FilepathCompactException
     */
    public function validateForImport(): string
    {
        if (!file_exists($this->filepath)) {
            throw FilepathCompactException::notFound($this->filepath);
        }

        if (!is_readable($this->filepath)) {
            throw FilepathCompactException::notReadable($this->filepath);
        }

        return $this->filepath;
    }

    /**
     * Validasi untuk export (directory harus writable)
     * 
     * @throws FilepathCompactException
     */
    public function validateForExport(bool $overwrite = false): string
    {
        $dir = dirname($this->filepath);

        if (!is_dir($dir) || !is_writable($dir)) {
            throw FilepathCompactException::notWritable($dir);
        }

        if (file_exists($this->filepath) && !$overwrite) {
            throw FilepathCompactException::notWritable($dir);
        }

        return $this->filepath;
    }

    /**
     * Validasi extension tambahan (optional)
     * 
     * @throws FilepathCompactException
     */
    public function mustBeJson(): self
    {
        $ext = strtolower(pathinfo($this->filepath, PATHINFO_EXTENSION));
        if ($ext !== 'json') {
            throw FilepathCompactException::invalidExtension($this->filepath, 'json');
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
            throw FilepathCompactException::notFile($this->filepath);
        }
        return $this;
    }

    public function mustBeDirectory(): self
    {
        if (!is_dir($this->filepath)) {
            throw FilepathCompactException::notDirectory($this->filepath);
        }
        return $this;
    }

    public function mustValidJson(): self
    {
        $this->mustBeJson();

        $content = file_get_contents($this->filepath);
        json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw FilepathCompactException::invalidJson($this->filepath, json_last_error_msg());
        }
        return $this;
    }

    public function mustValidCharacters(): self
    {
        if (preg_match('/[<>:"\|?*]/', $this->filepath)) {
            throw FilepathCompactException::invalidCharacters($this->filepath);
        }
        return $this;
    }

    public function mustNotEmpty(): self
    {
        if (empty($this->filepath)) {
            throw FilepathCompactException::emptyPath();
        }
        return $this;
    }
}
