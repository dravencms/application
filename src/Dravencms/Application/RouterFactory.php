<?php declare(strict_types = 1);
/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\Routing;

use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;

/**
 * Class RouteFactory
 * @package Salamek\Cms
 */
class RouterFactory
{
    /** @var array */
    private $routeFactories = [];

    /**
     * @param IRouterFactory $routeFactory
     */
    public function addRouteFactory(IRouterFactory $routeFactory): void
    {
        $this->routeFactories[] = $routeFactory;
    }

    /**
     * @return Router
     */
    public function createRouter(): Router
    {
        $router = new RouteList();
        foreach ($this->routeFactories AS $routeFactory) {
            $router[] = $routeFactory->createRouter();
        }

        return $router;
    }
}