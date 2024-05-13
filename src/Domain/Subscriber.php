<?php

namespace Notifications\Domain;

use Notifications\Domain\Exceptions\BadDataClassException;

class Subscriber
{
    public const DATABASE_FILE = 'keyDatabase.json';
    private const PATH = __DIR__ . '/resources/' . self::DATABASE_FILE;

    public function subscribe(Publisher $name): string
    {
        $message = "subscribed";

        return $message;
    }

    //public function registerSubInDatabase(string $name, array $keys): string
    //{
    //    $subscribers = [];
//
    //    if (file_exists(self::PATH)) {
    //        $subscribers = json_decode(file_get_contents(self::PATH), true);
    //    }
    //    else{
    //        new BadDataClassException();
    //    }
//
    //    $subscribers[$name]['VAPID'] = $keys;
    //    file_put_contents(self::PATH, json_encode($subscribers));
    //    return "registered";
    //}
}
