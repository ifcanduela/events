<?php

namespace ifcanduela\events;

trait CanEmitEvents
{
    /**
     * Emit an event.
     *
     * @param string|object $eventName
     */
    public function emit($eventName, $payload = null)
    {
        EventManager::trigger($eventName, $payload);
    }
}
