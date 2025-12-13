<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use SplFileInfo;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;

final class FileInfoPreset
{
    private SplFileInfo $fileInfo;

    public function __construct(
        private readonly CliStyle $io,
        private readonly string $filepath,
    ) {
        if (!file_exists($filepath) || !is_writable($filepath)) {
            WP_CLI::error(sprintf("File %s tidak ditemukan atau tidak dapat ditulis.", $filepath));
        }

        $this->fileInfo = new SplFileInfo($filepath);
    }

    public function renderList(): void
    {
        $io = $this->io;

        foreach ($this->fileAttributes() as $key => $value) {
            $io->line(sprintf("%s: %s", $key, $io->textYellow($value, 'y')));
        }
    }

    private function fileAttributes()
    {
        return [
            '📍 Path Lengkap' => $this->fileInfo->getRealPath(),
            '📄 Nama File' => $this->fileInfo->getFilename(),
            '🎯 Format' => strtoupper($this->fileInfo->getExtension()),
            '📦 Ukuran' => sprintf("%s (%s bytes)", $this->fileInfo->getSize(), number_format($this->fileInfo->getSize())),
            '🕐 Dibuat' => date('Y-m-d H:i:s', $this->fileInfo->getCTime()),
            '✏️  Dimodifikasi' => date('Y-m-d H:i:s', $this->fileInfo->getMTime()),
            '🔐 Permission' => $this->fileInfo->getPerms(),
            '🔢 Inode' => $this->fileInfo->getInode(),
        ];

        // WP_CLI::line(sprintf("💾 Device: %s", $this->fileInfo->getDevice()));
        // WP_CLI::line(sprintf("🔍 MD5 Checksum: %s", $this->fileInfo->getMD5()));
        // WP_CLI::line(sprintf("🔍 SHA1 Checksum: %s", $this->fileInfo->getSHA1()));
    }
}
