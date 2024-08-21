<?php

namespace Notifications\Domain\Exceptions;

class EmptySubscriberContentException extends LoggedException
{
    public const MESSAGE =
     "An EmptySubscriberContentException has occurred: Unable to perform operation due to empty content.";
    public const ERROR_CODE = 422;
}
