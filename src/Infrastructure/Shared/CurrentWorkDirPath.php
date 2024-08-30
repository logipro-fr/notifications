<?php

namespace Notifications\Infrastructure\Shared;

class CurrentWorkDirPath
{
    public static function getPath(): string|false
    {
        $path = getcwd();
        return $path ? realpath($path) : "";
    }
}
