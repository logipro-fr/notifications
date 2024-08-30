<?php

namespace Notifications\Domain\Exceptions;

class SubscriberNotFoundException extends LoggedException
{
    public const ERROR_CODE = 500;
}
