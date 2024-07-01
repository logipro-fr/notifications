<?php

namespace Notifications\Domain\Exceptions;

use Exception;

class EmptySubscriberContentException extends LoggedException
{
    public const MESSAGE =
     "An EmptyContentException has occurred: Unable to perform operation due to empty content.";
    public const ERROR_CODE = 422;
}
