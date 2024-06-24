<?php

namespace App\Service;

use function Symfony\Component\String\u;

class Explorer
{
    public static function getDirContents($dir, &$results = array(), bool $deep = true): array
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != ".." && $deep) {
                self::getDirContents($path, $results);
            }
        }
        return $results;
    }

    public static function findFileNames(string $directory, string $fileExtension, bool $asFileName = true): array
    {
        $allFiles = self::getDirContents($directory);
        $filesOfExtension = array_values(array_filter($allFiles, function (string $path) use ($fileExtension) {
            return u($path)->endsWith('.'.$fileExtension);
        }));

        $filesOfExtensionAsFileNames = array_map(fn(string $path) => str_replace('.'.$fileExtension, '', basename($path)), $filesOfExtension);

        return $asFileName ? $filesOfExtensionAsFileNames : $filesOfExtension;
    }
}