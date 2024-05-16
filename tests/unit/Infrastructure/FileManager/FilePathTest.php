<?php

namespace Notifications\Tests\Infrastructure\FileManager;

use Notifications\Infrastructure\FileManager\FilePath;
use PHPUnit\Framework\TestCase;

class FilePathTest extends TestCase
{
    public function testGetPath(): void
    {
        $mainPath = __DIR__;
        $fileToAnalyze = 'file.txt';
        $filePath = new FilePath($mainPath, $fileToAnalyze);

        $expectedPath = __DIR__ . '/resources/' . 'file.txt';
        $this->assertEquals($expectedPath, $filePath->getPath());
    }
}
