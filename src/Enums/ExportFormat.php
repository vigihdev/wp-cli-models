<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Enums;

enum ExportFormat: string
{
    case JSON = 'json';
    case CSV = 'csv';
        // case Xml = 'xml';
    case YAML = 'yaml';
    case IDS = 'ids';
    case MARKDOWN = 'markdown';
    case TXT = 'txt';
    case XLSX = 'xlsx';
}
