<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\UI;

use WP_CLI;

final class ColorStyle
{

    public function gren(string $message): string
    {
        return WP_CLI::colorize("%G{$message}%n");
    }
}
