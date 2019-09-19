# ifcanduela/events

Simple plug-and-play events library for any kind of project.

## Installation

Use [Composer](https://getcomposer.org):

```sh
composer require ifcanduela/events
```

## Usage

Mixin the `\ifcanduela\events\CanEmitEvents` or `\ifcanduela\events\CanListenToEvents` traits
into your classes and call the `emit()` or `listenTo()` methods.

## Emitting events

An object will be able to emit events by using the `CanEmitEvents` trait and its
`emit()` method.

An *event* may be a simple `string`, or an object of a class other than `stdClass`.
If the event is a `string`, an optional `$payload` may be supplied and will be
sent to the listener instead of the event string.

```php
class MyEventType
{
    public $someProperty;

    public function __construct(int $data)
    {
        $this->someProperty = $data;
    }
}

class MyEventEmitter
{
    use \ifcanduela\events\CanEmitEvents;

    public function createData()
    {
        $data = random_int();

        // using an object as event
        $this->emit(new MyEventType($data));

        // using a string as event name; the array will be sent to the listener
        $this->emit("data.created", ["data" => $data]);
    }
}
```

## Listening to events

An object will be able to listen to events by using the `CanListenToEvents` 
trait and calling `listenTo()`.

The event callback will receive the payload of the event, if any. In case of 
event objects, the `$payload` will be the event object itself, otherwise it
will be whichever payload was passed by the emitter.

```php
class MyEventListener
{
    public function __construct()
    {
        $this->listenTo(MyEventType::class, function ($event) {
            echo $event->someProperty;
        });

        $this->listenTo("data.created", function ($payload) {
            echo $payload["data"];
        });

        $this->listenTo("data.created", [$this, "eventHandler"]);
    }

    public function eventHandler($payload)
    {
        echo $payload["data"];
    }
}
```

## Using the EventManager directly

The event manager lets your code emit and listen to events from outside objects.
Replace `$object->emit()` with `EventManager::trigger()` and `$object->listenTo()`
with `EventManager::register()`. These two static methods are used under the 
hood by the traits, so the functionality is exactly the same.

```php
EventManager::register("event.name", function ($payload) {
    assert($payload["a"] === 1);
    assert($payload["b"] === 2);
});

EventManager::trigger("event.name", ["a" => 1, "b" => 2]);
```

## License

[MIT](LICENSE).
