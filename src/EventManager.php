<?php

namespace ifcanduela\events;

class EventManager
{
    public static $listeners = [];

    public static function trigger($eventName, $payload = null)
    {
        if (is_object($eventName)) {
            $payload = $eventName;
            $eventName = get_class($payload);

            if ($eventName === "stdClass") {
                throw new \Exception("Events of type `stdClass` are not supported");
            }
        }

        if (isset(static::$listeners[$eventName])) {
            foreach (static::$listeners[$eventName] as $callback) {
                $callback($payload);
            }
        }
    }

    public static function register(string $eventName, callable $callback)
    {
        if (!array_key_exists($eventName, static::$listeners)) {
            static::$listeners[$eventName] = [];
        }

        static::$listeners[$eventName][] = $callback;
    }
}
