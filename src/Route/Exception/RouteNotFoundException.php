<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Exception;
use Ramsey\Uuid\UuidInterface;

class RouteNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('Route with id "' . $id . '" not found.');
	}

	public static function byName(string $name): self
	{
		return new self('Route with name "' . $name . '" not found.');
	}
}
