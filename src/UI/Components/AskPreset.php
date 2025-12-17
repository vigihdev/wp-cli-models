<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;

final class AskPreset
{

    private const TAB = "    ";
    private const LINE = "\n";

    public function __construct(
        private readonly CliStyle $io,
    ) {}

    public function delete(string $dataLabel, array $dataItems, array $extraMessages, bool $hr = true): void
    {
        $io = $this->io;
        $io->line("📦 Data {$dataLabel} akan di hapus");

        // Pilihan data yang akan dihapus
        $io->log('');
        $io->line($io->textYellow("🟡 {$dataLabel} yang akan dihapus:"));
        if ($hr) {
            $io->hr('-', 75);
        }
        $itemsDefinitions = [];
        foreach ($dataItems as $name => $value) {
            $paddingData = str_repeat(' ', 4);
            $itemsDefinitions["{$paddingData}{$name}"] = $value;
        }
        $io->definitionList($itemsDefinitions);
        if ($hr) {
            $io->hr('-', 75);
        }

        // Peringatan perintah ini akan menghapus data secara permanen
        $io->log('');
        if ($hr) {
            $io->hr('-', 75);
        }
        $io->line($io->textYellow("⚠️ PERINGATAN:"));
        $items = [
            "• Data akan dihapus PERMANEN",
            "• Tidak bisa dikembalikan (no undo)",
        ];
        $items = array_merge($items, $extraMessages);

        foreach ($items as $item) {
            $padding = str_repeat(' ', 4);
            $io->line($io->textWarning("{$padding} {$item}"));
        }
        if ($hr) {
            $io->hr('-', 75);
        }
        $io->log('');

        // Konfirmasi perintah
        WP_CLI::confirm(
            sprintf("🔶 %s", $io->highlightText("Konfirmasi untuk melanjutkan")),
        );
    }


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
                $io->logError("Directory", $io->highlightText($baseDir), "baru gagal di buat.");

                $this->renderSarans(
                    "Periksa izin tulis (chmod/chown) pada path induk.",
                    "Periksa apakah path memiliki spasi.",
                    "Periksa apakah path memiliki karakter khusus."
                );

                $io->line($io->textYellow("⭕ Process di hentikan"));
                return false;
            }

            $io->line(
                $io->textSuccess("✔ Directory baru berhasil di buat")
            );

            return true;
        }

        if (! is_writable($baseDir)) {
            $io->log("");
            $io->logFatal("Directory", $io->highlightText($baseDir), "tidak dapat ditulis.");

            $this->renderSarans(
                "Periksa izin tulis (chmod/chown) pada path.",
                "Periksa apakah path memiliki spasi.",
                "Periksa apakah path memiliki karakter khusus."
            );

            $io->line($io->textYellow("⭕ Process di hentikan"));
            $io->hr();
            $io->log("");

            return false;
        }

        return true;
    }

    private function renderSarans(...$sarans)
    {

        $io = $this->io;
        $io->log("");
        $io->line($io->textGreen(self::TAB . "sarans: "));
        foreach ($sarans as $saran) {
            $io->line($io->textGreen(self::TAB . self::TAB . "✔ {$saran}", '%g'));
        }
        $io->log("");
    }
}
