<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\UI\CliStyle;

final class DryRunPresetExport
{
    private array $lines = [];
    public function __construct(
        private readonly CliStyle $io,
        private readonly string $format,
        private readonly string $name,
        private readonly int $total,
        private readonly ?string $output = null,
    ) {}

    public function addLine(...$lines): void
    {
        $io = $this->io;
        foreach ($lines as $line) {
            $this->lines[] = sprintf("%s", $io->textWarning("✔ {$line}", '%y'));
        }
    }

    public function renderLineInfo(...$lines): void
    {
        $io = $this->io;
        // Render total items
        $io->line(
            sprintf(
                "%s %s %s",
                $io->textWarning("✔ Total items yang akan di export", '%y'),
                $io->highlightText("({$this->total})"),
                $io->textWarning("{$this->name}", '%y'),
            )
        );

        foreach ($lines as $line) {
            $message = sprintf("%s", $io->textWarning("✔ {$line}", '%y'));
            $io->line($message);
        }
    }

    public function renderSummary(array $items, bool $withHr = true): void
    {
        $this->io->renderSummary($items, $withHr);
    }

    public function renderCompact(array $items, array $fields): void
    {
        $this->renderTitle();
        $this->renderTable($items, $fields);
        $this->renderFooter();
    }

    public function renderTitle(): void
    {

        $io = $this->io;
        $io->title("🔍 DRY RUN - Preview Data Export {$this->name}");

        // Render output file
        if ($this->output) {
            $output = Path::isAbsolute($this->output) ? $this->output : Path::join(getcwd() ?? '', $this->output);
            $io->note('Data akan diekspor ke file ' . $io->highlightText($output));
        }

        // Render total items
        // $io->line(
        //     sprintf(
        //         "%s %s %s",
        //         $io->textWarning("✔ Total items yang akan di export", '%y'),
        //         $io->highlightText("({$this->total})"),
        //         $io->textWarning("{$this->name}", '%y'),
        //     )
        // );

        // // Render lines
        // foreach ($this->lines as $line) {
        //     $io->line($line);
        // }

        // // 
        // $io->log('');
    }

    public function renderTable(array $items, array $fields): void
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

    public function renderFooter(): void
    {

        $io = $this->io;
        $io->hr('-', 75);
        $io->successWithIcon('Dry run selesai!');
        $io->block('Gunakan tanpa --dry-run untuk eksekusi sebenarnya.', 'note');
        $io->hr('-', 75);
    }
}
