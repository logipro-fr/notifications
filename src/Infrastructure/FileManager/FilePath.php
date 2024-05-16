<?php

namespace Notifications\Infrastructure\FileManager;

class FilePath
{
    private string $path;

    public function __construct(string $mainPath, string $fileToAnalyze)
    {
        $this->path = $mainPath . '/resources/' . $fileToAnalyze;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
