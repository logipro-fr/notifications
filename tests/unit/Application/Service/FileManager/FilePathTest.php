<?php

namespace Notifications\Tests\Application\Service\FileManager;

use PHPUnit\Framework\TestCase;
use Notifications\Application\Service\FileManager\FilePath;

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
