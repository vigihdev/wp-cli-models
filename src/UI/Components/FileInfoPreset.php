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

        $io->log('');
        $io->log('📁 Informasi File');
        $io->hr('-', 75);
        foreach ($this->fileAttributes() as $key => $value) {
            $io->line(sprintf("%s: %s", $key, $io->textGreen((string) $value, '%g')));
        }
        $io->hr('-', 75);
        $io->log('');
    }

    private function fileAttributes()
    {
        $permission = substr(sprintf('%o', $this->fileInfo->getPerms()), -4);
        $permission_symbolic = $this->permissionToSymbolic($this->fileInfo->getPerms());
        $owner = posix_getpwuid($this->fileInfo->getOwner());
        $group = posix_getgrgid($this->fileInfo->getGroup());

        $stat = stat($this->fileInfo->getRealPath());
        // Format size
        $size = $this->fileInfo->getSize();
        $size_human = $this->formatSize($size);
        return [
            '📍 Path Lengkap' => $this->fileInfo->getRealPath(),
            '📄 Nama File' => $this->fileInfo->getFilename(),
            '🎯 Format' => strtoupper($this->fileInfo->getExtension()),
            '📦 Ukuran' => sprintf("%s (%s bytes)", $size_human, number_format($size, 0)),
            '🕐 Dibuat' => date('Y-m-d H:i:s', $this->fileInfo->getCTime()),
            '✏️  Dimodifikasi' => date('Y-m-d H:i:s', $this->fileInfo->getMTime()),
            '🔐 Permission' => sprintf('%s (%s)', $permission, $permission_symbolic),
            '🔢 Inode' => $stat['ino'],
        ];
    }

    /**
     * Helper: Permission to symbolic
     */
    private function permissionToSymbolic($mode)
    {
        $symbolic = '';
        $permissions = array(
            array('read', 'r', 4),
            array('write', 'w', 2),
            array('execute', 'x', 1),
        );

        for ($i = 0; $i < 3; $i++) {
            $shift = (2 - $i) * 3;
            $octal = ($mode >> $shift) & 7;

            foreach ($permissions as $perm) {
                $symbolic .= ($octal & $perm[2]) ? $perm[1] : '-';
            }
        }

        return $symbolic;
    }

    /**
     * Helper: Format size
     */
    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
