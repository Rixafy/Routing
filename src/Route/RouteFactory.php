<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

class RouteFactory
{
    public function create(RouteData $routeData): Route
    {
        return new Route($routeData);
    }
}