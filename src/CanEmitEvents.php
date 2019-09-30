<?php

namespace ifcanduela\events;

trait CanEmitEvents
{
    /**
     * Emit an event.
     *
     * When using an object as first argument, it will double as event name
     * (using its class name) and payload.
     *
     * @param string|object $eventName
     * @return mixed
     */
    public function emit($eventName, $payload = null)
    {
        return EventManager::trigger($eventName, $payload);
    }
}
