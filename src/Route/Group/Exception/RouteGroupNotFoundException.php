<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class RouteGroupNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('RouteGroup with id "' . $id . '" not found.');
	}

	public static function byName(string $name): self
	{
		return new self('RouteGroup with name "' . $name . '" not found.');
	}
}
