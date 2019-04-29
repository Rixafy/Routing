<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Exception\RouteNotFoundException;

class RouteFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var RouteRepository */
    private $routeRepository;

    /** @var RouteFactory */
    private $routeFactory;

    /** @var RouteGenerator */
    private $routeGenerator;

    public function __construct(
		EntityManagerInterface $entityManager,
		RouteRepository $routeRepository,
		RouteFactory $routeFactory,
		RouteGenerator $routeGenerator
	) {
        $this->entityManager = $entityManager;
        $this->routeRepository = $routeRepository;
        $this->routeFactory = $routeFactory;
		$this->routeGenerator = $routeGenerator;
	}

    public function create(RouteData $routeData): Route
    {
        $route = $this->routeFactory->create($routeData);

        $this->entityManager->persist($route);
        $this->entityManager->flush();

        return $route;
    }

    public function generate(RouteData $routeData): Route
	{
		$route = $this->routeGenerator->generate($routeData);

		$this->entityManager->flush();

		return $route;
	}

    /**
     * @throws RouteNotFoundException
     */
    public function edit(UuidInterface $id, UuidInterface $routeGroupId, RouteData $routeData): Route
    {
        $route = $this->routeRepository->get($id, $routeGroupId);
        $route->edit($routeData);

        $this->entityManager->flush();

        return $route;
    }

    /**
     * @throws RouteNotFoundException
     */
    public function remove(UuidInterface $id, UuidInterface $routeGroupId): void
    {
        $route = $this->routeRepository->get($id, $routeGroupId);
        $this->entityManager->remove($route);

        $this->entityManager->flush();
    }

    /**
     * @throws RouteNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $routeGroupId): Route
    {
        return $this->routeRepository->get($id, $routeGroupId);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function getByName(string $name, UuidInterface $routeGroupId): Route
    {
        return $this->routeRepository->getByName($name, $routeGroupId);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function getByTarget(UuidInterface $targetId): Route
    {
        return $this->routeRepository->getByTarget($targetId);
    }

    public function getNameCounter(string $routeName, UuidInterface $siteId, UuidInterface $groupId = null): int
    {
        return $this->routeRepository->getNameCounter($routeName, $siteId, $groupId);
    }
}
