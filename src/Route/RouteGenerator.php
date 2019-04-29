<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Routing\Route\Exception\RouteNotFoundException;

class RouteGenerator
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

	public function generate(RouteData $routeData): Route
	{
		try {
			$route = $this->routeFacade->getByTarget($routeData->target, $routeData->group->getId());

			if ($routeData->name !== $route->getName()) {
				$nameCount = $this->routeFacade->getNameCounter($routeData->name, $routeData->site->getId());
				$route->increaseNameCounter($nameCount);
			}

			$route->edit($routeData);

		} catch (RouteNotFoundException $e) {
			$route = $this->routeFactory->create($routeData);
			$this->entityManager->persist($route);

			$nameCount = $this->routeFacade->getNameCounter($routeData->name, $routeData->site->getId());
			$route->increaseNameCounter($nameCount);
		}

		return $route;
	}
}
