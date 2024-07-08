<?php

namespace Notifications\Tests\Domain\Exceptions;

use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Exceptions\BadEndpointException;
use Notifications\Domain\Exceptions\LoggedException;
use Notifications\Infrastructure\Shared\CurrentWorkDirPath;
use PHPUnit\Framework\TestCase;

use function Safe\file_get_contents;
use function Safe\unlink;

class LoggedExceptionTest extends TestCase
{
    private string $logFilePath;
    private string $logDirPath;

    protected function setUp(): void
    {
        $this->logFilePath = CurrentWorkDirPath::getPath() . LoggedException::LOG_FILE_PATH;
        $this->logDirPath = dirname($this->logFilePath);
    }

    public function testLoggedException(): void
    {
        $this->deleteLogFile();
        new BadEndpointException("Log test", 0);

        $logs = file_get_contents($this->logFilePath);
        $this->assertStringEndsWith("0: Log test\n", $logs);
    }

    public function testCheckCanCreateLogFile(): void
    {
        $this->deleteLogFile();

        $sut = new LoggedException("a logged exception", 1);

        $this->assertFileExists($this->logFilePath);
    }

    public function isExceptionThrowed(): void
    {
        $this->expectException(LoggedException::class);
        new Endpoint("df");
    }

    private function deleteLogFile(): void
    {
        if (file_exists($this->logFilePath)) {
            unlink($this->logFilePath);
        }
    }

    public function testEnsureLogDirectoryExists(): void
    {
        $loggedExceptionTester = new LoggedExceptionTester("message", 0);

        if (is_dir($this->logDirPath)) {
            unlink($this->logFilePath);
            rmdir($this->logDirPath);
        }

        $loggedExceptionTester->publicEnsureLogDirectoryExists($this->logDirPath);

        $this->assertDirectoryExists($this->logDirPath);
    }

    public function testEnsureLogFileExists(): void
    {
        $loggedExceptionTester = new LoggedExceptionTester("message", 0);

        if (file_exists($this->logFilePath)) {
            unlink($this->logFilePath);
        }

        $loggedExceptionTester->publicEnsureLogFileExists($this->logFilePath);

        $this->assertFileExists($this->logFilePath);
    }
}