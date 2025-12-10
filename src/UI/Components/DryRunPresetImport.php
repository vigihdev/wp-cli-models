<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;

final class DryRunPresetImport
{
    public function __construct(
        private readonly CliStyle $io,
        private readonly string $filepath,
        private readonly string $name,
        private readonly int $total,
    ) {}

    public function renderTitle(): void
    {
        $io = $this->io;
        $io->title('🔍 DRY RUN - Preview Data Import');
        $io->note('Tidak ada perubahan ke database');
    }

    public function renderCompact(array $items, array $fields): void
    {
        $this->renderTitle();
        $this->renderTable($items, $fields);
        $this->renderDefinitionList();
        $this->renderFooter();
    }

    public function renderTable(array $items, array $fields): void
    {
        $io = $this->io;
        $io->table($items, $fields);
        $io->newLine();
    }

    public function renderDefinitionList()
    {
        $io = $this->io;
        $io->hr('-', 75);
        $io->definitionList([
            'Total ' . $this->name => (string) $this->total,
            'Mode' => 'Dry Run',
            'File' => basename($this->filepath)
        ]);
        $io->hr('-', 75);
        $io->newLine();
    }

    public function renderFooter(): void
    {
        $io = $this->io;
        $io->successWithIcon('Dry run selesai!');
        $io->block('Gunakan tanpa --dry-run untuk eksekusi sebenarnya.', 'note');
    }
}
