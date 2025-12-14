<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Exceptions\DirectoryException;

final class DirectoryValidator
{
    public function __construct(
        private readonly string $dirpath
    ) {}

    public static function validate(string $dirpath): static
    {
        return new self($dirpath);
    }

    /**
     * Validasi bahwa direktori ada
     * 
     * @throws DirectoryException
     */
    public function mustExist(): self
    {
        if (!file_exists($this->dirpath)) {
            throw DirectoryException::notFound($this->dirpath);
        }

        if (!is_dir($this->dirpath)) {
            throw DirectoryException::invalidPath($this->dirpath, 'Path bukan direktori');
        }

        return $this;
    }

    /**
     * Validasi bahwa direktori belum ada
     * 
     * @throws DirectoryException
     */
    public function mustNotExist(): self
    {
        if (file_exists($this->dirpath)) {
            throw DirectoryException::alreadyExists($this->dirpath);
        }

        return $this;
    }

    /**
     * Validasi bahwa direktori dapat dibaca
     * 
     * @throws DirectoryException
     */
    public function mustBeReadable(): self
    {
        $this->mustExist();

        if (!is_readable($this->dirpath)) {
            throw DirectoryException::notReadable($this->dirpath);
        }

        return $this;
    }

    /**
     * Validasi bahwa direktori dapat ditulis
     * 
     * @throws DirectoryException
     */
    public function mustBeWritable(): self
    {
        $this->mustExist();

        if (!is_writable($this->dirpath)) {
            throw DirectoryException::notWritable($this->dirpath);
        }

        return $this;
    }

    /**
     * Validasi bahwa direktori kosong
     * 
     * @throws DirectoryException
     */
    public function mustBeEmpty(): self
    {
        $this->mustExist();

        $files = scandir($this->dirpath);
        $fileCount = count(array_diff($files, ['.', '..']));

        if ($fileCount > 0) {
            throw DirectoryException::notEmpty($this->dirpath, $fileCount);
        }

        return $this;
    }

    /**
     * Validasi bahwa direktori tidak kosong
     * 
     * @throws DirectoryException
     */
    public function mustNotBeEmpty(): self
    {
        $this->mustExist();

        $files = scandir($this->dirpath);
        $fileCount = count(array_diff($files, ['.', '..']));

        if ($fileCount === 0) {
            throw DirectoryException::invalidPath($this->dirpath, 'Direktori kosong');
        }

        return $this;
    }

    /**
     * Validasi path direktori valid
     * 
     * @throws DirectoryException
     */
    public function mustHaveValidPath(): self
    {
        // Cek karakter tidak valid
        if (preg_match('/[<>:"\|?*]/', $this->dirpath)) {
            throw DirectoryException::invalidPath($this->dirpath, 'Path mengandung karakter tidak valid');
        }

        // Cek panjang path
        if (strlen($this->dirpath) > 255) {
            throw DirectoryException::invalidPath($this->dirpath, 'Path terlalu panjang (maksimal 255 karakter)');
        }

        return $this;
    }

    /**
     * Validasi untuk create direktori
     * 
     * @throws DirectoryException
     */
    public function validateForCreate(bool $recursive = false): self
    {
        $this->mustHaveValidPath();
        $this->mustNotExist();

        // Jika tidak recursive, parent directory harus ada
        if (!$recursive) {
            $parentDir = dirname($this->dirpath);
            if (!is_dir($parentDir)) {
                throw DirectoryException::createFailed(
                    $this->dirpath,
                    'Parent directory tidak ada. Gunakan recursive mode.'
                );
            }

            if (!is_writable($parentDir)) {
                throw DirectoryException::notWritable($parentDir);
            }
        }

        return $this;
    }

    /**
     * Validasi untuk delete direktori
     * 
     * @throws DirectoryException
     */
    public function validateForDelete(bool $force = false): self
    {
        $this->mustExist();

        // Jika tidak force, direktori harus kosong
        if (!$force) {
            $this->mustBeEmpty();
        }

        // Cek permission parent directory
        $parentDir = dirname($this->dirpath);
        if (!is_writable($parentDir)) {
            throw DirectoryException::notWritable($parentDir);
        }

        return $this;
    }

    /**
     * Validasi untuk read operations
     * 
     * @throws DirectoryException
     */
    public function validateForRead(): self
    {
        $this->mustExist();
        $this->mustBeReadable();

        return $this;
    }

    /**
     * Validasi untuk write operations
     * 
     * @throws DirectoryException
     */
    public function validateForWrite(): self
    {
        $this->mustExist();
        $this->mustBeWritable();

        return $this;
    }

    /**
     * Get directory path
     */
    public function getPath(): string
    {
        return $this->dirpath;
    }

    /**
     * Get file count in directory
     */
    public function getFileCount(): int
    {
        if (!is_dir($this->dirpath)) {
            return 0;
        }

        $files = scandir($this->dirpath);
        return count(array_diff($files, ['.', '..']));
    }
}
