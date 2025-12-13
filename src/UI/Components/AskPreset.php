<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;

final class AskPreset
{

    public function __construct(
        private readonly CliStyle $io,
    ) {}

    /**
     * Ask user to choose a preset
     */
    public function directory(string $path): bool
    {
        $output = Path::isAbsolute($path) ? $path : Path::join(getcwd() ?? '', $path);
        $baseDir = Path::getDirectory($output);

        $io = $this->io;
        if (! is_dir($baseDir)) {
            $io->line("📦 Directory baru akan di buat {$io->highlightText($baseDir)}");
            WP_CLI::confirm(
                sprintf("🔶 %s", $io->highlightText("Konfirmasi untuk melanjutkan"))
            );

            if (! mkdir($baseDir, 0755, true)) {
                $io->line(
                    $io->textRed("‼️  Directory baru gagal di buat. Saran: Periksa izin tulis (chmod/chown) pada path induk.")
                );

                $io->line(
                    $io->textRed("⭕ Process di hentikan")
                );

                return false;
            }

            $io->line(
                $io->textSuccess("✔ Directory baru berhasil di buat")
            );

            return true;
        }

        if (! is_writable($baseDir)) {
            $io->line(
                implode(" ", [
                    $io->textRed("[FATAL]"),
                    "❌ Directory",
                    $io->highlightText($baseDir),
                    "tidak dapat dituliskan.",
                    $io->highlightText("Saran: Periksa izin tulis (chmod/chown) pada path.")
                ])
            );

            $io->line(
                $io->textYellow("⭕ Process di hentikan")
            );

            $io->hr();
            $io->log("");

            return false;
        }

        return true;
    }
}
