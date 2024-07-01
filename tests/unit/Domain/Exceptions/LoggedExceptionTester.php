<?php

namespace Notifications\Tests\Domain\Exceptions;

use Notifications\Domain\Exceptions\LoggedException;

class LoggedExceptionTester extends LoggedException
{
    public function publicEnsureLogDirectoryExists(string $directoryPath): void
    {
        $this->ensureLogDirectoryExists($directoryPath);
    }

    public function publicEnsureLogFileExists(string $filePath): void
    {
        $this->ensureLogFileExists($filePath);
    }
}
