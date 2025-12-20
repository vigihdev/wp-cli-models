<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI\Components;

use Vigihdev\WpCliModels\UI\CliStyle;

final class DryRunPreset
{
    /**
     * @var array $lineInfos List of line info messages
     */
    private array $lineInfos = [];

    /**
     * @var bool $hrDefinition Whether to render horizontal line between items
     */
    private bool $hrDefinition = true;

    /**
     * @var array $itemsDefinition List of definition items
     */
    private array $itemsDefinition = [];

    /**
     * @var array $warningValidations List of warning validations
     */
    private array $warningValidations = [];

    private array $itemsTable = [];

    public function __construct(
        private readonly CliStyle $io,
        private readonly string $sectionName,
    ) {}

    /**
     * Set definition list
     *
     * @param array $items List of definition items
     * @param bool $hr Whether to render horizontal line between items
     * @return self
     */
    public function addDefinition(array $items, bool $hr = true): self
    {
        $this->hrDefinition = $hr;
        $this->itemsDefinition = $items;
        return $this;
    }

    /**
     * Set warning validations
     *
     * @param array $errors
     * @return self
     */
    public function addWarningValidation(array $errors): self
    {
        $this->warningValidations = $errors;
        return $this;
    }

    /**
     * Render the dry run preset
     *
     * @return void
     */
    public function render(): void
    {
        $io = $this->io;
        $this->renderTitle();

        // Render line info
        foreach ($this->lineInfos as $line) {
            $io->line($line);
        }
        $io->newLine();

        // Render warnings
        if (! empty($this->warningValidations)) {
            $io->line(
                $io->textYellow("⚠️ WARNINGS:")
            );
            foreach ($this->warningValidations as $field => $message) {
                $padding = str_repeat(' ', 4);
                $io->line(
                    $io->textWarning("{$padding}{$field}: {$message}")
                );
            }
            $io->newLine();
        }

        // Render items table
        if (! empty($this->itemsTable)) {
            $io->table($this->itemsTable['rows'], $this->itemsTable['headers']);
            $io->newLine();
        }

        // Render definition list
        if (! empty($this->itemsDefinition)) {
            $io->definitionList($this->itemsDefinition, $this->hrDefinition);
            $io->newLine();
        }

        // Render footer
        $this->renderFooter();
    }

    public function addInfo(...$lines): self
    {
        $io = $this->io;
        foreach ($lines as $line) {
            $line = trim((string) $line);
            if (strlen($line) === 0) {
                continue;
            }
            $this->lineInfos[] = sprintf("%s %s", $io->textSuccess("✔"), $io->textYellow("{$line}", '%y'));
        }

        return $this;
    }

    public function addTable(array $items): self
    {
        $headers = array_keys($items[0] ?? []);
        $rows = [];
        foreach ($items as $item) {
            foreach ($item as $key => $value) {
                if (is_array($value)) {
                    $value = implode(', ', $value);
                    $item[$key] = $value;
                }
            }

            $rows[] = array_values($item);
        }

        $this->itemsTable = [
            'headers' => $headers,
            'rows' => $rows,
        ];

        return $this;
    }

    private function renderTitle(): void
    {
        $io = $this->io;
        $io->title("🔍 DRY RUN - Preview Data {$this->sectionName}");
        $io->note('Tidak ada perubahan ke database');
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
