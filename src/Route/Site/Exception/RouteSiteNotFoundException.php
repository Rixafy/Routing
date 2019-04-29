<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Site\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class RouteSiteNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('RouteGroup with id "' . $id . '" not found.');
	}

	public static function byDomainHost(string $domainHost): self
	{
		return new self('RouteGroup with domain_host "' . $domainHost . '" not found.');
	}
}
