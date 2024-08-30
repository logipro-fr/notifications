<?php

namespace Notifications\Domain\Exceptions;

class BadDataClassException extends \Exception
{
    public const EXCEPTION_FILE_NOT_FOUND = 'File not found: ';
    public const EXCEPTION_DECODING_ERROR = 'Error decoding JSON in: ';
    public const EXCEPTION_FORMAT_ERROR = 'Invalid JSON structure';
}
