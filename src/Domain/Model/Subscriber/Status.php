<?php

namespace Notifications\Domain\Model\Subscriber;

enum Status: string
{
    case SUBSCRIBED = "granted";
    case UNKNOW = "unknow";
    case DENIED = "denied";
}
