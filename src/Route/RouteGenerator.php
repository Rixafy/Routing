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

	public function generate(RouteData $data): Route
	{
		try {
			$route = $this->routeFacade->getByTarget($data->target, $data->group->getId());

			if ($data->name !== $route->getNameInSite()) {
				$route->edit($data);

				$siteNameCount = $this->routeFacade->getNameCounter($data->name, $data->site->getId());
				$route->increaseSiteNameCounter($siteNameCount);

				$groupNameCount = $this->routeFacade->getNameCounter($data->name, $data->site->getId(), $data->group->getId());
				$route->increaseGroupNameCounter($groupNameCount);
			}

		} catch (RouteNotFoundException $e) {
			$route = $this->routeFactory->create($data);
			$this->entityManager->persist($route);

			$siteNameCount = $this->routeFacade->getNameCounter($data->name, $data->site->getId());
			$route->increaseSiteNameCounter($siteNameCount);

			$groupNameCount = $this->routeFacade->getNameCounter($data->name, $data->site->getId(), $data->group->getId());
			$route->increaseGroupNameCounter($groupNameCount);
		}

		return $route;
	}
}
