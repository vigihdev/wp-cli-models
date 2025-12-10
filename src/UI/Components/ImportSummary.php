<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;

final class ImportSummary
{
    public int $success = 0;
    public int $skipped = 0;
    public int $failed = 0;

    public function addSuccess(): void
    {
        $this->success++;
    }
    public function addSkipped(): void
    {
        $this->skipped++;
    }
    public function addFailed(): void
    {
        $this->failed++;
    }

    public function render(CliStyle $io, string $filepath, float $execTime): void
    {
        $total = $this->success + $this->skipped + $this->failed;

        $io->newLine(2);
        $io->title('📋 SUMMARY IMPORT');
        $io->hr();

        $io->table(
            [
                ['✅ Berhasil', $this->success],
                ['⏭  Dilewati', $this->skipped],
                ['❌ Gagal',   $this->failed],
            ],
            ['Status', 'Count']
        );

        $execTime = number_format($execTime, 2);

        $io->newLine();
        $io->definitionList([
            '⏱  Waktu Eksekusi' => "{$execTime} detik",
            '📁 File Source'   => basename($filepath),
            '📄 Total Data'    => (string) $total,
        ]);

        // Message Footer
        if ($this->failed === 0 && $this->success > 0) {
            $io->newLine();
            $io->block('🎉 Import selesai dengan sukses!', 'success');
        } elseif ($this->success > 0) {
            $io->newLine();
            $io->block("ℹ️ Import selesai dengan {$this->failed} error.", 'warning');
        } else {
            $io->newLine();
            $io->block('ℹ️  Tidak ada data yang diimport.', 'warning');
        }
    }
}
