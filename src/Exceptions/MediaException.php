<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

/**
 * Class MediaException - Exception untuk handling error media
 */
final class MediaException extends WpCliModelException
{
    /**
     * Membuat exception untuk upload media yang gagal
     *
     * @param string $filename Nama file yang gagal diupload
     * @param string|null $errorMessage Pesan error dari sistem
     * @return self Instance MediaException
     */
    public static function uploadFailed(string $filename, ?string $errorMessage = null): self
    {
        $message = sprintf('Upload file "%s" gagal', $filename);
        if ($errorMessage) {
            $message .= sprintf(': %s', $errorMessage);
        }

        return new self(
            message: $message,
            context: [
                'filename' => $filename,
                'error' => $errorMessage
            ],
            solutions: [
                'Periksa permission direktori upload',
                'Pastikan ukuran file tidak melebihi batas',
                'Verifikasi tipe file yang diizinkan'
            ]
        );
    }

    /**
     * Membuat exception untuk ukuran file yang melebihi batas
     *
     * @param string $filename Nama file
     * @param int $fileSize Ukuran file dalam bytes
     * @param int $maxSize Batas maksimum ukuran file dalam bytes
     * @return self Instance MediaException
     */
    public static function fileSizeExceeded(string $filename, int $fileSize, int $maxSize): self
    {
        return new self(
            message: sprintf(
                'Ukuran file "%s" (%s) melebihi batas maksimum (%s)',
                $filename,
                self::formatBytes($fileSize),
                self::formatBytes($maxSize)
            ),
            context: [
                'filename' => $filename,
                'file_size' => $fileSize,
                'max_size' => $maxSize,
                'file_size_formatted' => self::formatBytes($fileSize),
                'max_size_formatted' => self::formatBytes($maxSize)
            ],
            solutions: [
                'Kompres file sebelum upload',
                'Tingkatkan batas upload_max_filesize di php.ini',
                sprintf('Gunakan file dengan ukuran maksimal %s', self::formatBytes($maxSize))
            ]
        );
    }

    /**
     * Membuat exception untuk tipe file yang tidak diizinkan
     *
     * @param string $filename Nama file
     * @param string $fileType Tipe/MIME type file
     * @param array $allowedTypes Daftar tipe file yang diizinkan
     * @return self Instance MediaException
     */
    public static function fileTypeNotAllowed(string $filename, string $fileType, array $allowedTypes): self
    {
        return new self(
            message: sprintf(
                'Tipe file "%s" (%s) tidak diizinkan',
                $filename,
                $fileType
            ),
            context: [
                'filename' => $filename,
                'file_type' => $fileType,
                'allowed_types' => $allowedTypes
            ],
            solutions: [
                'Gunakan salah satu tipe file yang diizinkan: ' . implode(', ', $allowedTypes),
                'Konversi file ke format yang didukung'
            ]
        );
    }

    /**
     * Membuat exception untuk file yang tidak ditemukan
     *
     * @param string $filePath Path file yang tidak ditemukan
     * @return self Instance MediaException
     */
    public static function fileNotFound(string $filePath): self
    {
        return new self(
            message: sprintf('File tidak ditemukan: %s', $filePath),
            context: ['file_path' => $filePath],
            solutions: [
                'Periksa path file yang diberikan',
                'Pastikan file belum dihapus atau dipindahkan',
                'Verifikasi permission akses ke file'
            ]
        );
    }

    /**
     * Membuat exception untuk media attachment yang tidak valid
     *
     * @param int $attachmentId ID attachment
     * @param string $reason Alasan attachment tidak valid
     * @return self Instance MediaException
     */
    public static function invalidAttachment(int $attachmentId, string $reason): self
    {
        return new self(
            message: sprintf('Attachment ID %d tidak valid: %s', $attachmentId, $reason),
            context: [
                'attachment_id' => $attachmentId,
                'reason' => $reason
            ],
            solutions: [
                'Verifikasi attachment ID ada di database',
                'Gunakan wp media list untuk melihat daftar attachment',
                'Periksa apakah attachment sudah dihapus'
            ]
        );
    }

    /**
     * Format ukuran bytes menjadi format yang mudah dibaca
     *
     * @param int $bytes Ukuran dalam bytes
     * @param int $precision Presisi angka desimal
     * @return string Ukuran yang sudah diformat
     */
    private static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
