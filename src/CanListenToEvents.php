<?php

namespace ifcanduela\events;

trait CanListenToEvents
{
    function listenTo(string $eventName, callable $callback)
    {
        EventManager::register($eventName, $callback);
    }
}
