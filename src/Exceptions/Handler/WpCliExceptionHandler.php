<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Exceptions\Handler;

use Throwable;
use Vigihdev\WpCliModels\Exceptions\WpCliModelException;
use Vigihdev\WpCliModels\UI\CliStyle;

final class WpCliExceptionHandler implements HandlerExceptionInterface
{
    private const TAB = '     ';
    private const SPACE = '  ';
    private const LINE = "\n";

    private CliStyle $io;
    public function handle(CliStyle $io, Throwable $e): void
    {
        $this->io = $io;
        if ($e instanceof WpCliModelException) {
            $this->printException($e);
            return;
        }

        $io->error(
            'Unexpected error: ' . $e->getMessage()
        );
    }

    private function printException(WpCliModelException $e): void
    {

        $io = $this->io;
        $contexts = $e->getContext();
        $solutions = $e->getSolutions();

        $message = $e->getMessage();

        // Jika ada konteks, tambahkan ke pesan
        if (is_array($contexts) && count($contexts) > 0) {
            foreach ($contexts as $key => $value) {
                $message .= self::LINE;
                $message .= self::TAB . $key . ' : ';
                $value = is_array($value) ? implode(', ', $value) : $value;
                $message .= $io->highlightText((string) $value);
                // $message .= self::LINE . self::TAB . self::SPACE . $io->highlightText((string) $value);
            }
        }

        // Jika ada solusi, tambahkan ke pesan
        if (is_array($solutions) && count($solutions) > 0) {
            $message .= self::LINE;
            $message .= self::LINE;
            $message .= self::TAB . $io->textGreen('Saran :');

            foreach ($solutions as $solution) {
                $message .= self::LINE . self::TAB . self::SPACE . "{$io->textGreen((string)$solution, '%g')}";
            }
        }


        $io->error($message);
    }
}
