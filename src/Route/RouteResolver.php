<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

class RouteResolver
{
	/** @var RouteFacade */
	private $routeFacade;

	public function __construct(RouteFacade $routeFacade)
	{
		$this->routeFacade = $routeFacade;
	}

	public function handle(RouteData $routeData): Route
	{
		//TODO: Finish handling
		try {
			$existing = $this->routeFacade->getByTarget($routeData->target, $routeData->group->getId());

			if ($existing->getName() === $routeData->name) {
				return $existing;
			} else {

			}
		} catch (RouteNotFoundException $e) {
			try {
				$existing = $this->routeFacade->getByName($routeData->name, $routeData->group->getId());

				if ($existing->getTarget() === $routeData->target) {
					return $existing;
				}
			} catch (RouteNotFoundException $e) {
			}
		}
	}
}
