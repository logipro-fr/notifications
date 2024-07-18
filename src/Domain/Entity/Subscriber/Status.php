<?php

namespace Notifications\Domain\Entity\Subscriber;

enum Status: string
{
    case SUBSCRIBED = "granted";
    case UNKNOW = "unknow";
    case DENIED = "denied";
}
