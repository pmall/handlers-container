# Container request handlers resolver

This package provides a resolver producing Psr-15 request handlers from class names using a Psr-11 container.

**Require** php >= 7.1

**Installation** `composer require ellipse/handlers-container`

**Run tests** `./vendor/bin/kahlan`

- [Using the container request handlers resolver](#using-the-container-request-handlers-resolver)

## Using the container request handlers resolver

Please note the request handler instances are resolved using [ellipse/container-reflection](https://github.com/ellipsephp/container-reflection) auto-wiring feature.

Also when instances of `Psr\Http\Message\ServerRequestInterface` are needed to build a request handler instance, the one received by its `->handle()` method is injected.

```php
<?php

namespace App;

use Some\Psr11Container;

use Ellipse\Handlers\ContainerResolver;

use App\Handlers\SomeRequestHandler;

// Get a Psr-11 container.
$container = new Psr11Container;

// Create a resolver with the Psr-11 container and a delegate for non request handler class name elements.
$resolver = new ContainerResolver($container, function ($element) {

    // $element is not a request handler class name, just return it.

    return $element;

});

// Produce a Psr-15 request handler from a request handler class name.
$handler = $resolver(SomeRequestHandler::class);
```
