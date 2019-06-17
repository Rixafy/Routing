<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group;

class RouteGroupFactory
{
	public function create(RouteGroupData $routeGroupData): RouteGroup
	{
		return new RouteGroup($routeGroupData);
	}
}
