<?php

namespace App;

use function file_put_contents;
use function json_encode;
use function ksort;
use function stream_get_line;
use function substr;
use function strlen;
use function strpos;

final class Parser
{
    public function parse(string $inputPath, string $outputPath): void
    {
        $handle = fopen($inputPath, 'r');

        $baseUrl = 'https://stitcher.io';
        $baseUrlLength = strlen($baseUrl);

        $data = [];
        while($line = stream_get_line($handle, 4096, \PHP_EOL)) {
            $comma = strpos($line, ',');
            $path = substr($line, $baseUrlLength, $comma - $baseUrlLength);
            $year = substr($line, -25, 10);
            $data[$path][$year] = ($data[$path][$year] ?? 0) + 1;
        }

        foreach($data as &$paths) {
            ksort($paths);
        }

        file_put_contents($outputPath, json_encode($data, \JSON_PRETTY_PRINT));
    }


}