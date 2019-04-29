<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
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
			$route = $this->update($data->target, $data->name);

		} catch (RouteNotFoundException $e) {
			$route = $this->create($data);
		}

		return $route;
	}

	public function create(RouteData $data): Route
	{
		$route = $this->routeFactory->create($data);
		$this->entityManager->persist($route);

		$siteNameCount = $this->routeFacade->getNameCounter($data->name, $data->site->getId());
		$route->increaseSiteNameCounter($siteNameCount);

		$groupNameCount = $this->routeFacade->getNameCounter($data->name, $data->site->getId(), $data->group->getId());
		$route->increaseGroupNameCounter($groupNameCount);

		return $route;
	}

	/**
	 * @throws RouteNotFoundException
	 */
	public function update(UuidInterface $targetId, string $name): Route
	{
		$route = $this->routeFacade->getByTarget($targetId);

		if ($name !== $route->getName()) {
			$route->changeName($name);

			$siteNameCount = $this->routeFacade->getNameCounter($name, $route->getSite()->getId());
			$route->increaseSiteNameCounter($siteNameCount);

			$groupNameCount = $this->routeFacade->getNameCounter($name, $route->getSite()->getId(), $route->getGroup()->getId());
			$route->increaseGroupNameCounter($groupNameCount);
		}

		return $route;
	}
}
