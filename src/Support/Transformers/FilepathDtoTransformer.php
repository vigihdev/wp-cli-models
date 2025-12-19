<?php

declare(strict_types=1);

namespace Vigihdev\WpCliModels\Support\Transformers;

use RuntimeException;
use Serializer\Factory\JsonTransformerFactory;
use Vigihdev\WpCliModels\Exceptions\ContentFetchException;

final class FilepathDtoTransformer
{

    /**
     * Transform JSON string to DTO object or array
     *
     * @param string $json JSON string
     * @param string $dtoClass DTO class name
     *
     * @return object|array DTO object or array
     */
    public static function fromJson(string $json, string $dtoClass): object|array
    {
        return self::dtoTransform($json, $dtoClass);
    }

    /**
     * Transform JSON string to DTO object or array
     *
     * @param string $filepath Filepath to JSON file
     * @param string $dtoClass DTO class name
     *
     * @return object|array DTO object or array
     */
    public static function fromFileJson(string $filepath, string $dtoClass): object|array
    {

        if (!is_file($filepath)) {
            throw ContentFetchException::fileNotFound($filepath);
        }

        if (!is_readable($filepath)) {
            throw ContentFetchException::unreadableFile($filepath);
        }

        $json = file_get_contents($filepath);

        if (trim($json) === '') {
            throw ContentFetchException::emptyFile($filepath);
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ContentFetchException::invalidJson($filepath);
        }

        return self::dtoTransform($json, $dtoClass);
    }

    /**
     * Transform CSV string to DTO object or array
     *
     * @param string $filepath Filepath to CSV file
     * @param string $dtoClass DTO class name
     *
     * @return object|array DTO object or array
     */
    public static function fromFileCsv(string $filepath, string $dtoClass): array
    {
        if (!is_file($filepath)) {
            throw ContentFetchException::fileNotFound($filepath);
        }

        if (!is_readable($filepath)) {
            throw ContentFetchException::unreadableFile($filepath);
        }

        // cepat, aman, handle error lewat exception manual
        $rows = @file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($rows === false) {
            throw ContentFetchException::invalidCsv($filepath);
        }

        // CSV kosong
        if (count($rows) === 0) {
            throw ContentFetchException::emptyFile($filepath);
        }

        $data = [];
        foreach ($rows as $index => $line) {
            $cols = str_getcsv($line);

            // minimal 1 kolom
            if (empty($cols)) {
                throw ContentFetchException::invalidCsv($filepath);
            }

            $header = explode(',', $rows[0]);
            if (is_int($index) && $index > 0) {
                $data[] = array_combine($header, $cols);
            }
        }

        if (empty($data)) {
            return $data;
        }

        $json = json_encode($data);

        return self::dtoTransform($json, $dtoClass);
    }

    /**
     * Transform JSON string to DTO object or array
     *
     * @param string $json JSON string
     * @param string $dtoClass DTO class name
     *
     * @return object|array DTO object or array
     */
    private static function dtoTransform(string $json, string $dtoClass): object|array
    {
        try {
            $json = trim($json);
            $transformer = JsonTransformerFactory::create($dtoClass);
            if (substr($json, 0, 1) === '[') {
                return $transformer->transformArrayJson($json);
            }
            return $transformer->transformJson($json);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                sprintf("Gagal transform from %s %s", $json, $e->getMessage())
            );
        }
    }
}
