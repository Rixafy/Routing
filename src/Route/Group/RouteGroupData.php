<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group;

use Rixafy\Routing\Route\Site\RouteSite;

class RouteGroupData
{
    /** @var string */
    public $name;

    /** @var string */
    public $prefix = '/';

    /** @var RouteSite */
    public $site;

    public function __construct(RouteSite $routeSite)
	{
		$this->site = $routeSite;
	}
}
