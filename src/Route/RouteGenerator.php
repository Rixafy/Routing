<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Exception\RouteNotFoundException;

class RouteGenerator
{
	/** @var RouteRepository */
	private $routeRepository;

	/** @var RouteFactory */
	private $routeFactory;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(
		RouteRepository $routeRepository,
		EntityManagerInterface $entityManager,
		RouteFactory $routeFactory
	) {
		$this->routeRepository = $routeRepository;
		$this->entityManager = $entityManager;
		$this->routeFactory = $routeFactory;
	}

	/**
	 * @deprecated
	 */
	public function generate(RouteData $data): Route
	{
		try {
			$route = $this->update($data->target, $data->name);

		} catch (RouteNotFoundException $e) {
			$route = $this->create($data);
		}

		return $route;
	}

	/**
	 * @deprecated
	 */
	public function create(RouteData $data): Route
	{
		$route = $this->routeFactory->create($data);
		$this->entityManager->persist($route);

		$siteNameCount = $this->routeRepository->getNameCounter($data->name, $data->site->getId());
		$route->increaseSiteNameCounter($siteNameCount);

		$groupNameCount = $this->routeRepository->getNameCounter($data->name, $data->site->getId(), $data->group->getId());
		$route->increaseGroupNameCounter($groupNameCount);

		return $route;
	}

	/**
	 * @throws RouteNotFoundException
	 * @deprecated
	 */
	public function update(UuidInterface $targetId, string $name): Route
	{
		$route = $this->routeRepository->getByTarget($targetId);

		if ($name !== $route->getName()) {
			$route->changeName($name);

			$siteNameCount = $this->routeRepository->getNameCounter($name, $route->getSite()->getId());
			$route->increaseSiteNameCounter($siteNameCount);

			$groupNameCount = $this->routeRepository->getNameCounter($name, $route->getSite()->getId(), $route->getGroup()->getId());
			$route->increaseGroupNameCounter($groupNameCount);
		}

		return $route;
	}
}
