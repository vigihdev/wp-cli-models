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

    public function renderCompact(CliStyle $io, string $filepath, float $execTime): void
    {
        $this->renderTitle($io);
        $this->renderTable($io);
        $this->renderDefinitionList($io, $filepath, $execTime);
        $this->renderFooter($io);
    }

    public function renderTitle(CliStyle $io): void
    {
        $io->newLine();
        $io->title('📋 SUMMARY IMPORT');
    }

    public function renderTable(CliStyle $io): void
    {

        $io->table(
            [
                ['✅ Berhasil', $this->success],
                ['⏭  Dilewati', $this->skipped],
                ['❌ Gagal',   $this->failed],
            ],
            ['Status', 'Count']
        );
    }

    public function renderDefinitionList(CliStyle $io, string $filepath, float $execTime)
    {
        $total = $this->success + $this->skipped + $this->failed;
        $execTime = number_format($execTime, 2);

        $io->newLine();
        $io->definitionList([
            '⏱  Waktu Eksekusi' => "{$execTime} detik",
            '📁 File Source'   => basename($filepath),
            '📄 Total Data'    => (string) $total,
        ]);
    }

    public function renderFooter(CliStyle $io): void
    {
        if ($this->failed === 0 && $this->success > 0) {
            $io->newLine();
            $io->block('🎉 Import selesai dengan sukses!', 'success');
        } elseif ($this->success > 0) {
            $io->newLine();
            $io->block("ℹ️  Import selesai dengan {$this->failed} error.", 'warning');
        } else {
            $io->newLine();
            $io->block('ℹ️  Tidak ada data yang diimport.', 'warning');
        }
    }
}
