<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Routing\Route\Exception\DuplicateRouteException;
use Rixafy\Routing\Route\Exception\RouteNotFoundException;

class RouteResolver
{
	/** @var RouteFacade */
	private $routeFacade;

	/** @var RouteFactory */
	private $routeFactory;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(
		RouteFacade $routeFacade,
		EntityManagerInterface $entityManager,
		RouteFactory $routeFactory
	) {
		$this->routeFacade = $routeFacade;
		$this->entityManager = $entityManager;
		$this->routeFactory = $routeFactory;
	}

	/**
	 * @throws DuplicateRouteException
	 */
	public function handle(RouteData $routeData): Route
	{
		try {
			$route = $this->routeFacade->getByTarget($routeData->target, $routeData->group->getId());

			if ($route->getName() === $routeData->name) {
				return $route;

			} else {
				$route->addPreviousName($route->getName());
				$route->edit($routeData);
			}

		} catch (RouteNotFoundException $e) {
			try {
				$this->routeFacade->getByName($routeData->name, $routeData->group->getId());
				throw new DuplicateRouteException('Route "' . $routeData->name . '" already exists.');

			} catch (RouteNotFoundException $e) {
				$route = $this->routeFacade->create($routeData);
				$this->entityManager->persist($route);
			}
		}

		return $route;
	}
}
