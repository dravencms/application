# DravenCMS Application

Core Nette application integration shared by DravenCMS packages. It provides the base presenter and control classes, package route aggregation, common flash-message constants, template lookup conventions, and WebLoader defaults.

## Features

- `Dravencms\BasePresenter` and `Dravencms\Components\BaseControl\BaseControl`.
- A central router assembled from package `IRouterFactory` services.
- DravenCMS presenter mapping and frontend error-presenter defaults.
- SCSS and CSS URL processing for WebLoader.
- Shared template and layout lookup conventions.

## Installation

```bash
composer require dravencms/application
```

The DravenCMS package loader reads the bundled `dravencms.config.neon`. Without the loader, include that file manually from the application configuration.

The default configuration expects public assets under `%wwwDir%/assets` and writes generated bundles to `%wwwDir%/webtemp`.

## Adding Package Routes

Implement `IRouterFactory` in a package:

```php
use Dravencms\Application\IRouterFactory;
use Nette\Application\Routers\RouteList;

final class RouteFactory implements IRouterFactory
{
    public function createRouter(): RouteList
    {
        $routes = new RouteList('Front');
        $routes->addRoute('example', 'Example:default');
        return $routes;
    }
}
```

Register it with the shared router:

```neon
services:
    exampleRouteFactory: App\Example\RouteFactory
    route:
        setup:
            - addRouteFactory(@exampleRouteFactory)
```

Route factories are evaluated in registration order, so package configuration order can affect matching priority.

## Base Classes

Application presenters should extend `Dravencms\BasePresenter`, or a more specific frontend/admin presenter derived from it. Reusable UI controls can extend `BaseControl`; it shares DravenCMS template conventions and ACL attribute handling.

## License

This package is licensed under the LGPL-3.0 license.
