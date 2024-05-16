<?php

namespace Notifications\Infrastructure\Subscriber;

class SubscriberManagerInDatabase{
    public const DATABASE_FILE = 'keyDatabase.json';
    public const PATH = __DIR__ . '/resources/' . self::DATABASE_FILE;
    public function registerSubInDatabase(string $name, mixed $keys): string
    {
        $subscribers = [];
        $fileContents = @file_get_contents(self::PATH);
        //if ($fileContents !== false) {
        //    $subscribers = json_decode($fileContents, true);
        //}
        $subscribers[$name]['VAPID'] = $keys;
        @file_put_contents(self::PATH, json_encode($subscribers));
        return "registered";
    }
}