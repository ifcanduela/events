<?php

namespace ifcanduela\events;

trait CanListenToEvents
{
    /**
     * Listen to an event.
     *
     * Event listeners can return values:
     *
     * - `null` will have no effect
     * - any other value will short-circuit the event and be returned, remaining
     *   callbacks will not be called
     *
     * @param string $eventName
     * @param callable $callback
     */
    function listenTo(string $eventName, callable $callback)
    {
        EventManager::register($eventName, $callback);
    }
}
