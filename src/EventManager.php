<?php

namespace ifcanduela\events;

class EventManager
{
    public static $listeners = [];

    /**
     * Trigger an event and call the registered callbacks.
     *
     * When using an object as first argument, it will double as event name
     * (using its class name) and payload.
     *
     * The list of callbacks can be short-circuited if a callback returns a
     * value other than `null`.
     *
     * @param string|object $eventName
     * @return mixed
     */
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
                $result = $callback($payload);

                if ($result !== null) {
                    return $result;
                }
            }
        }
    }

    /**
     * Register an event listener.
     *
     * Event listeners can return a value:
     *
     * - `null` will have no effect
     * - any other value will short-circuit the event and be returned, remaining
     *   callbacks will not be called
     *
     * @param string $eventName
     * @param callable $callback
     */
    public static function register(string $eventName, callable $callback)
    {
        if (!array_key_exists($eventName, static::$listeners)) {
            static::$listeners[$eventName] = [];
        }

        static::$listeners[$eventName][] = $callback;
    }
}
