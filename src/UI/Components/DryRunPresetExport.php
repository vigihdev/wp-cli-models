<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\UI\CliStyle;

final class DryRunPresetExport
{
    public function __construct(
        private readonly CliStyle $io,
        private readonly string $format,
        private readonly string $name,
        private readonly int $total,
        private readonly ?string $output = null,
    ) {}


    public function renderCompact(array $items, array $fields): void
    {
        $this->renderTitle();
        // $this->renderTable($items, $fields);
        // $this->renderDefinitionList();
        $this->renderFooter();
    }

    private function renderTitle(): void
    {

        $io = $this->io;
        $io->title("🔍 DRY RUN - Preview Data Export {$this->name}");

        if ($this->output) {
            $output = Path::isAbsolute($this->output) ? $this->output : Path::join(getcwd() ?? '', $this->output);
            $io->note('Data akan diekspor ke file ' . $io->highlightText($output));
        }

        $io->line(
            sprintf("%s Total file yang akan di export %s %s", $io->textGreen("✔"), $io->highlightText("({$this->total})"), $this->name)
        );
    }

    private function renderTable(array $items, array $fields): void
    {
        $io = $this->io;
        $io->table($items, $fields);
        $io->newLine();
    }

    private function renderDefinitionList()
    {
        $io = $this->io;
        $io->hr('-', 75);
        $io->definitionList([
            'Total ' . $this->name => (string) $this->total,
            'Mode' => 'Dry Run',
            'File' => basename($this->output ?? ''),
        ]);
        $io->hr('-', 75);
        $io->newLine();
    }

    private function renderFooter(): void
    {

        $io = $this->io;
        $io->hr('-', 75);
        $io->successWithIcon('Dry run selesai!');
        $io->block('Gunakan tanpa --dry-run untuk eksekusi sebenarnya.', 'note');
        $io->hr('-', 75);
    }
}
