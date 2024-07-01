<?php

namespace Notifications\Domain\Exceptions;

use Exception;
use Notifications\Infrastructure\Shared\CurrentWorkDirPath;
use Safe\DateTime;

use function Safe\error_log;
use function Safe\fclose;
use function Safe\mkdir;

class LoggedException extends \Exception
{
    public const LOG_FILE_PATH = "/log/exceptions.log";
    private const LOG_PATTERN = "[%s] %d: %s\n";

    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);

        $logFilePath = CurrentWorkDirPath::getPath() . self::LOG_FILE_PATH;
        $this->ensureLogDirectoryExists(dirname($logFilePath));
        $this->ensureLogFileExists($logFilePath);
        $this->writeToLogFile($message, $code, $logFilePath);
    }

    protected function ensureLogDirectoryExists(string $directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }
    }

    protected function ensureLogFileExists(string $filePath): void
    {
        if (!file_exists($filePath)) {
            $fileHandle = fopen($filePath, 'c+b');
            if ($fileHandle === false) {
                throw new Exception("Failed to open or create log file: $filePath");
            }
            fclose($fileHandle);
        }
    }

    private function writeToLogFile(string $message, int $code, string $logFilePath): void
    {
        $formattedMessage = sprintf(self::LOG_PATTERN, (new DateTime())->format("d.m.Y H:i:s"), $code, $message);
        error_log($formattedMessage, 3, $logFilePath);
    }
}
