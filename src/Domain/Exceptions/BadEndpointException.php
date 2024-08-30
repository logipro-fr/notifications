<?php

namespace Notifications\Domain\Exceptions;

use Exception;

class BadEndpointException extends LoggedException
{
    public const ERROR_CODE = 422;
}
