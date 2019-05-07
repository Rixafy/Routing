<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Site;

class RouteSiteData
{
	/** @var string */
	public $domainHost;

	public function __construct(string $domainHost)
	{
		$this->domainHost = $domainHost;
	}
}
