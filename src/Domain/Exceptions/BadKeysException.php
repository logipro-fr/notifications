<?php

namespace Notifications\Domain\Exceptions;

use Exception;

class BadKeysException extends LoggedException
{
    public const ERROR_CODE = 422;
}
