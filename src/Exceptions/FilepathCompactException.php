<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions;

final class FilepathCompactException extends WpCliModelException
{

    public static function notFound(string $filepath): self
    {
        return new self(
            message: "File tidak ditemukan",
            context: [
                'filepath' => $filepath,
            ],
            solutions: [
                "Periksa path file",
                "Pastikan file ada",
                "Periksa izin tulis (chmod/chown)"
            ]
        );
    }

    public static function notReadable(string $filepath): self
    {
        return new self(
            message: "File tidak dapat dibaca",
            context: [
                'filepath' => $filepath,
            ],
            solutions: [
                "Periksa permission file: chmod +r " . basename($filepath)
            ]
        );
    }

    public static function notWritable(string $filepath): self
    {
        return new self(
            message: "File tidak dapat ditulis",
            context: [
                'filepath' => $filepath,
            ],
            solutions: [
                "Periksa permission file atau gunakan --overwrite"
            ]
        );
    }

    public static function invalidExtension(string $filepath, string $expected): self
    {
        $actual = pathinfo($filepath, PATHINFO_EXTENSION) ?: 'none';
        return new self(
            message: sprintf("File harus berekstensi .%s", $expected),
            context: [
                'filepath' => $filepath,
            ],
            solutions: [
                sprintf("Ekstensi saat ini: .%s", $actual),
                "Ubah ekstensi file atau gunakan file yang sesuai"
            ]
        );
    }


    public static function invalidJson(string $filepath, ?string $error = null): self
    {
        $message = "Format JSON tidak valid";
        if ($error) {
            $message .= ": " . $error;
        }

        return new self(
            message: $message,
            context: [
                'filepath' => $filepath,
            ],
            solutions: ["Validasi file menggunakan jsonlint.com"]
        );
    }

    public static function notDirectory(string $filepath): self
    {
        return new self(
            message: "Path harus berupa direktori",
            context: ['filepath' => $filepath],
            solutions: ["Gunakan path direktori yang valid"]
        );
    }

    public static function notFile(string $filepath): self
    {
        return new self(
            message: "Path harus berupa file (bukan direktori)",
            context: ['filepath' => $filepath],
            solutions: [
                "Gunakan path file yang valid",
                "Pastikan path tidak berakhir dengan slash (/) jika dimaksudkan sebagai file"
            ]
        );
    }


    public static function emptyPath(): self
    {
        return new self(
            message: "Path file tidak boleh kosong",
            solutions: ["Berikan path file yang valid"]
        );
    }

    public static function invalidCharacters(string $path): self
    {
        return new self(
            message: "Path mengandung karakter tidak valid",
            context: [
                'filepath' => $path
            ],
            solutions: [
                "Hindari karakter khusus dalam path",
                "Gunakan hanya huruf, angka, dan underscore (_)"
            ]
        );
    }


    public static function tooLong(string $path, int $maxLength): self
    {
        return new self(
            message: sprintf('Path melebihi panjang maksimum (%d karakter)', $maxLength),
            context: ['filepath' => $path, 'length' => strlen($path)],
            solutions: ['Persingkat path atau tingkatkan batas maksimum']
        );
    }

    public static function containsSpaces(string $path): self
    {
        return new self(
            message: 'Path mengandung spasi',
            context: ['filepath' => $path],
            solutions: ['Ganti spasi dengan underscore (_) atau dash (-)']
        );
    }

    public static function relativePathNotAllowed(string $path): self
    {
        return new self(
            message: 'Path relatif tidak diizinkan',
            context: ['filepath' => $path],
            solutions: ['Gunakan path absolut']
        );
    }

    public static function reservedFilename(string $path): self
    {
        return new self(
            message: 'Nama file termasuk reserved name di sistem operasi',
            context: ['filepath' => $path],
            solutions: ['Ganti nama file yang tidak menggunakan kata reserved (con, aux, prn, dll)']
        );
    }
}
