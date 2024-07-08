<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Publisher\Publisher;

abstract class AbstractFactoryPushApi
{
    abstract public function buildApi(Publisher $publisher): ApiInterface;
}
