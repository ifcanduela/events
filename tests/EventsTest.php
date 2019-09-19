<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

use ifcanduela\events\CanEmitEvents;
use ifcanduela\events\CanListenToEvents;
use ifcanduela\events\EventManager;


class EmitterObject { use CanEmitEvents; }
class ListenerObject { use CanListenToEvents; }

class DataCreatedEvent
{
    public $value;
    public function __construct($value) { $this->value = $value; }
}

final class EventsTest extends TestCase
{
    /**
     * @covers \ifcanduela\events\CanEmitEvents
     * @covers \ifcanduela\events\CanListenToEvents
     * @covers \ifcanduela\events\EventManager
     * @return null
     */
    public function testEmitAndListenToEventsUsingTraits(): void
    {
        $emitter = new EmitterObject();
        $listener = new ListenerObject();
        $n = random_int(0, 9999);
        $event = new DataCreatedEvent($n);

        $listener->listenTo(DataCreatedEvent::class, function (DataCreatedEvent $event) {
            $event->value += 1;
        });
        $emitter->emit($event);

        $this->assertEquals($n + 1, $event->value);
    }

    /**
     * @covers \ifcanduela\events\EventManager
     * @return null
     */
    public function testEmitAndListenToEventsUsingTheEventManager()
    {
        $test = 0;
        EventManager::register("event.name", function ($payload) use (&$test) {
            $test = $payload;
        });

        EventManager::trigger("event.name", 1);

        $this->assertEquals(1, $test);
    }

    /**
     * @covers \ifcanduela\events\EventManager
     * @return null
     */
    public function testRejectStdClassEvents()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Events of type `stdClass` are not supported");

        $test = 0;
        EventManager::register("stdClass", function ($payload) use (&$test) {
            $test = $payload["value"];
        });

        EventManager::trigger((object) ["value" => 1]);

        $this->assertEquals(1, $test);
    }
}
