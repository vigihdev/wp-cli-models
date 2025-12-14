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
            solution: "Periksa path file dan pastikan file ada"
        );
    }

    public static function notReadable(string $filepath): self
    {
        return new self(
            message: "File tidak dapat dibaca",
            context: [
                'filepath' => $filepath,
            ],
            solution: "Periksa permission file: chmod +r " . basename($filepath)
        );
    }

    public static function notWritable(string $filepath): self
    {
        return new self(
            message: "File tidak dapat ditulis",
            context: [
                'filepath' => $filepath,
            ],
            solution: "Periksa permission file atau gunakan --overwrite"
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
            solution: sprintf("Ekstensi saat ini: .%s", $actual)
        );
    }

    public static function invalidExtensionJson(string $filepath): self
    {
        $actual = pathinfo($filepath, PATHINFO_EXTENSION) ?: 'none';

        return new self(
            message: "File harus berekstensi .json",
            context: [
                'filepath' => $filepath,
            ],
            solution: sprintf("Ekstensi saat ini: .%s", $actual)
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
            solution: "Validasi file menggunakan jsonlint.com"
        );
    }
}
