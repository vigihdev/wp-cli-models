<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Validators;

use Vigihdev\WpCliModels\Exceptions\MediaException;

final class MediaValidator
{
    /**
     * Membuat instance MediaValidator baru
     *
     * @param string|int|null $source Sumber media (path file, URL, atau attachment ID)
     */
    public function __construct(
        private readonly string|int|null $source = null
    ) {}

    /**
     * Factory method untuk membuat validator
     *
     * @param string|int|null $source Sumber media
     * @return static Instance MediaValidator
     */
    public static function validate(string|int|null $source = null): static
    {
        return new self($source);
    }

    /**
     * Memvalidasi bahwa file dapat diupload
     *
     * @param string $filename Nama file yang akan diupload
     * @param string|null $errorMessage Pesan error dari sistem
     * @throws MediaException Jika upload gagal
     * @return self
     */
    public function mustUploadSuccessfully(string $filename, ?string $errorMessage = null): self
    {
        if ($errorMessage !== null) {
            throw MediaException::uploadFailed($filename, $errorMessage);
        }

        return $this;
    }

    /**
     * Memvalidasi ukuran file tidak melebihi batas maksimum
     *
     * @param string $filename Nama file
     * @param int $fileSize Ukuran file dalam bytes
     * @param int $maxSize Batas maksimum ukuran file dalam bytes
     * @throws MediaException Jika ukuran file melebihi batas
     * @return self
     */
    public function mustNotExceedFileSize(string $filename, int $fileSize, int $maxSize): self
    {
        if ($fileSize > $maxSize) {
            throw MediaException::fileSizeExceeded($filename, $fileSize, $maxSize);
        }

        return $this;
    }

    /**
     * Memvalidasi tipe file diizinkan
     *
     * @param string $filename Nama file
     * @param string $fileType Tipe/MIME type file
     * @param array $allowedTypes Daftar tipe file yang diizinkan
     * @throws MediaException Jika tipe file tidak diizinkan
     * @return self
     */
    public function mustBeAllowedFileType(string $filename, string $fileType, array $allowedTypes): self
    {
        if (!in_array($fileType, $allowedTypes, true)) {
            throw MediaException::fileTypeNotAllowed($filename, $fileType, $allowedTypes);
        }

        return $this;
    }

    /**
     * Memvalidasi bahwa file ada di path yang ditentukan
     *
     * @param string $filePath Path file yang akan dicek
     * @throws MediaException Jika file tidak ditemukan
     * @return self
     */
    public function mustExist(string $filePath): self
    {
        if (!file_exists($filePath)) {
            throw MediaException::fileNotFound($filePath);
        }

        return $this;
    }

    /**
     * Memvalidasi bahwa attachment ID valid
     *
     * @param int $attachmentId ID attachment
     * @param string|null $reason Alasan tambahan jika tidak valid
     * @throws MediaException Jika attachment tidak valid
     * @return self
     */
    public function mustBeValidAttachment(int $attachmentId, ?string $reason = null): self
    {
        $attachment = get_post($attachmentId);

        if (!$attachment || $attachment->post_type !== 'attachment') {
            $reason = $reason ?: 'Attachment tidak ditemukan di database';
            throw MediaException::invalidAttachment($attachmentId, $reason);
        }

        return $this;
    }
}
