<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Site;

class RouteSiteFactory
{
	public function create(RouteSiteData $routeData): RouteSite
	{
		return new RouteSite($routeData);
	}
}
